<?php
namespace agumil\SatuSehatSDK\Tests\Auth;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Exception\SSOauth2Exception;
use agumil\SatuSehatSDK\Tests\Support\EnvIsolation;
use agumil\SatuSehatSDK\Tests\Support\FakeOauth2;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

/**
 * Mengunci perilaku endpoint token.
 *
 * Dua bug yang pernah ada di sini tidak terlihat dari membaca kode dengan
 * saksama: `new Client()` tanpa opsi timeout sama sekali, dan `catch` yang
 * hanya menangkap `ClientException` sehingga `ConnectException` (timeout,
 * DNS gagal) serta `ServerException` (5xx) bocor ke aplikasi pemanggil.
 */
class Oauth2TokenTest extends TestCase
{
    use EnvIsolation;

    protected function setUp(): void
    {
        $this->isolateEnvironment();
    }

    protected function tearDown(): void
    {
        $this->restoreEnvironment();
    }

    public function testTimeoutIsSentToTokenEndpoint(): void
    {
        $history = [];
        $oauth2 = $this->oauth2($this->config(), $this->tokenResponse(), $history);

        $this->assertSame('tok-1', $oauth2->getToken());
        $this->assertCount(1, $history);
        $this->assertSame(Oauth2::DEFAULT_TIMEOUT, $history[0]['options']['timeout']);
        $this->assertSame(Oauth2::DEFAULT_CONNECT_TIMEOUT, $history[0]['options']['connect_timeout']);
    }

    public function testTimeoutIsConfigurable(): void
    {
        $history = [];
        $oauth2 = $this->oauth2($this->config(['timeout' => 4, 'connect_timeout' => 2]), $this->tokenResponse(), $history);

        $oauth2->getToken();

        $this->assertSame(4, $history[0]['options']['timeout']);
        $this->assertSame(2, $history[0]['options']['connect_timeout']);
    }

    public function testZeroTimeoutIsRejected(): void
    {
        // timeout 0 berarti "tunggu selamanya" di Guzzle: persis bug yang
        // sedang ditutup, jadi nilainya tidak boleh diterima.
        $this->expectException(SSDataTypeException::class);

        new FakeOauth2($this->config(['timeout' => 0]));
    }

    public function testConnectionFailureBecomesOauth2Exception(): void
    {
        $oauth2 = $this->oauth2($this->config(), [
            new ConnectException(
                'cURL error 28: Operation timed out after 15001 milliseconds',
                new Request('POST', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken')
            ),
        ]);

        $this->expectException(SSOauth2Exception::class);
        $this->expectExceptionMessage('Unable to reach token endpoint');

        $oauth2->getToken();
    }

    public function testServerErrorBecomesOauth2ExceptionNotGuzzleException(): void
    {
        $oauth2 = $this->oauth2($this->config(), [new PsrResponse(503, [], 'Service Unavailable')]);

        try {
            $oauth2->getToken();
            $this->fail('5xx seharusnya melempar SSOauth2Exception.');
        } catch (SSOauth2Exception $e) {
            $this->assertStringContainsString('HTTP 503', $e->getMessage());
            $this->assertStringContainsString('Service Unavailable', $e->getMessage());
        }
    }

    public function testClientErrorKeepsServerMessage(): void
    {
        $body = '{"error":"invalid_client","error_description":"Client authentication failed"}';
        $oauth2 = $this->oauth2($this->config(), [new PsrResponse(401, [], $body)]);

        $this->expectException(SSOauth2Exception::class);
        $this->expectExceptionMessage('HTTP 401');

        $oauth2->getToken();
    }

    public function testClientSecretIsNeverPartOfExceptionMessage(): void
    {
        $secret = 'rahasia-yang-tidak-boleh-bocor';
        $oauth2 = $this->oauth2(
            $this->config(['client_secret' => $secret]),
            [new ConnectException(
                'proxy rejected request for client_secret=' . $secret,
                new Request('POST', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken')
            )]
        );

        try {
            $oauth2->getToken();
            $this->fail('Seharusnya melempar SSOauth2Exception.');
        } catch (SSOauth2Exception $e) {
            $this->assertStringNotContainsString($secret, $e->getMessage());
            $this->assertStringContainsString('[REDACTED]', $e->getMessage());
        }
    }

    public function testNonJsonSuccessResponseIsRejected(): void
    {
        // Proxy/gateway bisa membalas 200 dengan halaman HTML. Dulu itu menjadi
        // token null yang baru ketahuan jauh di tengah request.
        $oauth2 = $this->oauth2($this->config(), [new PsrResponse(200, ['Content-Type' => 'text/html'], '<html>login</html>')]);

        $this->expectException(SSOauth2Exception::class);
        $this->expectExceptionMessage('not recognized');

        $oauth2->getToken();
    }

    public function testLongErrorBodyIsTruncated(): void
    {
        $oauth2 = $this->oauth2($this->config(), [new PsrResponse(502, [], str_repeat('x', 5000))]);

        try {
            $oauth2->getToken();
            $this->fail('Seharusnya melempar SSOauth2Exception.');
        } catch (SSOauth2Exception $e) {
            $this->assertLessThan(700, strlen($e->getMessage()));
        }
    }

    public function testTokenIsNotRequestedTwiceWhileValid(): void
    {
        $history = [];
        $oauth2 = $this->oauth2($this->config(), $this->tokenResponse(), $history);

        $oauth2->getToken();
        $oauth2->getToken();

        $this->assertCount(1, $history);
    }

    private function config(array $overrides = []): array
    {
        return array_merge([
            'environment' => 'staging',
            'organization_id' => 'org-1',
            'client_id' => 'client-1',
            'client_secret' => 'secret-1',
        ], $overrides);
    }

    private function tokenResponse(): array
    {
        return [new PsrResponse(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'tok-1',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ]))];
    }

    private function oauth2(array $config, array $responses, ?array &$history = null): FakeOauth2
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $history = [];
        $stack->push(Middleware::history($history));

        return (new FakeOauth2($config))->useClient(new Client(['handler' => $stack]));
    }
}

<?php
namespace agumil\SatuSehatSDK\Tests\Request;

use agumil\SatuSehatSDK\Tests\Support\EnvIsolation;
use agumil\SatuSehatSDK\Tests\Support\FakeOauth2;
use agumil\SatuSehatSDK\Tests\Support\MemoryLogger;
use agumil\SatuSehatSDK\Tests\Support\TestableHttpRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

/**
 * Menguji jalur log secara utuh: payload nyata -> PhiRedactor -> logger.
 *
 * Unit test PhiRedactor hanya membuktikan fungsinya benar; test ini
 * membuktikan `HttpRequest` benar-benar memakainya, karena kebocoran PHI
 * terjadi di sambungan antara keduanya.
 */
class HttpRequestLoggingTest extends TestCase
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

    public function testPatientIdentityNeverReachesTheLog(): void
    {
        $logger = new MemoryLogger();
        $http = $this->httpRequest($logger);

        $http->post('https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1/Patient', [
            'resourceType' => 'Patient',
            'active' => true,
            'identifier' => [
                ['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => '3171022809990001'],
            ],
            'name' => [['text' => 'Budi Santoso']],
            'birthDate' => '1990-09-28',
            'text' => ['div' => '<div>Pasien Budi Santoso, NIK 3171022809990001</div>'],
            'contact' => [['telecom' => [['value' => '081234567890']]]],
        ]);

        $logged = $logger->dump();

        $this->assertStringNotContainsString('Budi', $logged);
        $this->assertStringNotContainsString('3171022809990001', $logged);
        $this->assertStringNotContainsString('1990-09-28', $logged);
        $this->assertStringNotContainsString('081234567890', $logged);

        // Log tetap berguna: jenis resource dan struktur payload masih terlihat.
        $this->assertStringContainsString('Patient', $logged);
        $this->assertStringContainsString('identifier', $logged);
    }

    public function testSearchUrlInLogDoesNotContainNik(): void
    {
        $logger = new MemoryLogger();
        $http = $this->httpRequest($logger);

        $http->get('https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1/Patient?identifier=nik%7C3171022809990001&_count=10');

        $logged = $logger->dump();

        $this->assertStringNotContainsString('3171022809990001', $logged);
        $this->assertStringContainsString('_count=10', $logged);
    }

    public function testRawStringBodyIsLoggedBySizeOnly(): void
    {
        $logger = new MemoryLogger();
        $http = $this->httpRequest($logger);

        $http->post('https://api-satusehat-stg.dto.kemkes.go.id/kyc/v1/generate-url', 'PESAN-RAHASIA-PASIEN');

        $logged = $logger->dump();

        $this->assertStringNotContainsString('PESAN-RAHASIA-PASIEN', $logged);
        $this->assertStringContainsString('payload_bytes', $logged);
    }

    private function httpRequest(MemoryLogger $logger): TestableHttpRequest
    {
        $oauth2 = (new FakeOauth2([
            'environment' => 'staging',
            'organization_id' => 'org-1',
            'client_id' => 'client-1',
            'client_secret' => 'secret-1',
        ]))->useClient($this->client([$this->tokenResponse()]));

        $http = new TestableHttpRequest($oauth2, ['logger' => $logger]);
        $http->useClient($this->client([new PsrResponse(200, [], '{}')]));

        return $http;
    }

    private function tokenResponse(): PsrResponse
    {
        return new PsrResponse(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'tok-1',
            'expires_in' => 3600,
        ]));
    }

    private function client(array $responses): Client
    {
        return new Client(['handler' => HandlerStack::create(new MockHandler($responses))]);
    }
}

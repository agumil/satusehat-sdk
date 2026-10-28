<?php
namespace agumil\SatuSehatSDK\Tests\KYC;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Tests\Support\EnvIsolation;
use agumil\SatuSehatSDK\Tests\Support\FakeHttpRequest;
use agumil\SatuSehatSDK\Tests\Support\FakeKyc;
use PHPUnit\Framework\TestCase;

/**
 * `X-Debug-Mode` melewati verifikasi pasien pada alur KYC. Header itu harus
 * ditolak di sisi SDK saat environment-nya production, bukan hanya
 * mengandalkan penolakan server.
 */
class KycDebugModeTest extends TestCase
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

    public function testDebugModeIsRejectedInProduction(): void
    {
        $http = new FakeHttpRequest();
        $kyc = $this->kyc('production', $http);

        try {
            $kyc->requestPerNik('3171022809990001', 'Budi Santoso', 'frame-token', true);
            $this->fail('X-Debug-Mode seharusnya ditolak di production.');
        } catch (SSEnvException $e) {
            $this->assertStringContainsString('X-Debug-Mode', $e->getMessage());
        }

        // Ditolak sebelum request dikirim: tidak ada data pasien yang keluar.
        $this->assertSame([], $http->calls);
    }

    public function testDebugModeIsSentInStaging(): void
    {
        $http = new FakeHttpRequest();
        $kyc = $this->kyc('staging', $http);

        $kyc->requestPerNik('3171022809990001', 'Budi Santoso', 'frame-token', true);

        $this->assertCount(1, $http->calls);
        $this->assertSame('1', $http->calls[0]['headers']['X-Debug-Mode']);
        $this->assertSame('frame-token', $http->calls[0]['headers']['X-Frame-Token']);
    }

    public function testDebugModeIsSentInDevelopment(): void
    {
        $http = new FakeHttpRequest();
        $kyc = $this->kyc('development', $http);

        $kyc->requestPerNik('3171022809990001', 'Budi Santoso', 'frame-token', true);

        $this->assertSame('1', $http->calls[0]['headers']['X-Debug-Mode']);
    }

    public function testProductionRequestWithoutDebugModeHasNoDebugHeader(): void
    {
        $http = new FakeHttpRequest();
        $kyc = $this->kyc('production', $http);

        $kyc->requestPerNik('3171022809990001', 'Budi Santoso', 'frame-token');

        $this->assertCount(1, $http->calls);
        $this->assertArrayNotHasKey('X-Debug-Mode', $http->calls[0]['headers']);
    }

    public function testDebugModeIsAlsoRejectedForRawChallengePayload(): void
    {
        $http = new FakeHttpRequest();
        $kyc = $this->kyc('production', $http);

        $this->expectException(SSEnvException::class);

        $kyc->generateChallengeCode([
            'metadata' => ['method' => 'request_per_nik'],
            'data' => ['nik' => '3171022809990001', 'name' => 'Budi Santoso'],
        ], 'frame-token', true);
    }

    private function kyc(string $environment, FakeHttpRequest $http): FakeKyc
    {
        $oauth2 = new Oauth2([
            'environment' => $environment,
            'organization_id' => 'org-1',
            'client_id' => 'client-1',
            'client_secret' => 'secret-1',
        ]);

        return new FakeKyc($oauth2, ['environment' => $environment, FakeKyc::HTTP_KEY => $http]);
    }
}

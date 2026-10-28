<?php
namespace agumil\SatuSehatSDK\Tests\Auth;

use agumil\SatuSehatSDK\Auth\FileTokenStore;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Tests\Support\EnvIsolation;
use agumil\SatuSehatSDK\Tests\Support\FakeOauth2;
use agumil\SatuSehatSDK\Tests\Support\PosixFileTokenStore;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

/**
 * Token store berbasis file menyimpan kredensial dan menentukan organisasi
 * mana yang menerima data klinis. Dua hal yang diuji di sini:
 *
 * 1. File hanya boleh dibaca pemilik proses (mode 0600 benar-benar diperiksa).
 * 2. File yang ditulis pihak lain harus ditolak, bukan dipakai. Tanpa ini,
 *    token organisasi lain dapat mengarahkan data pasien ke organisasi yang
 *    salah — bukan kebocoran data, tetapi kesalahan tujuan data.
 */
class FileTokenStoreTest extends TestCase
{
    use EnvIsolation;

    private string $dir;

    protected function setUp(): void
    {
        $this->isolateEnvironment();

        $this->dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ss-token-' . bin2hex(random_bytes(6));

        if (!mkdir($this->dir, 0700, true)) {
            $this->fail('Tidak bisa membuat direktori sementara untuk test.');
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
            @chmod($file, 0600);
            @unlink($file);
        }

        @rmdir($this->dir);

        $this->restoreEnvironment();
    }

    public function testSignedTokenSurvivesRoundTrip(): void
    {
        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');
        $expiresAt = time() + 3600;
        $store->set('tok-1', $expiresAt);

        $this->assertSame(['token' => 'tok-1', 'expires_at' => $expiresAt], $store->get());
    }

    public function testFileWithoutSignatureIsRejectedWhenKeyIsKnown(): void
    {
        // Persis bentuk file yang bisa ditulis pihak lain.
        $this->writeFile(['token' => 'token-organisasi-lain', 'expires_at' => time() + 3600]);

        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');

        $this->assertNull($store->get());
    }

    public function testFileSignedWithAnotherKeyIsRejected(): void
    {
        $attacker = new FileTokenStore($this->path(), 'kunci-penyerang');
        $attacker->set('token-organisasi-lain', time() + 3600);

        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');

        $this->assertNull($store->get());
    }

    public function testTamperedExpiryIsRejected(): void
    {
        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');
        $store->set('tok-1', time() + 60);

        $data = $this->readFile();
        $data['expires_at'] = time() + 86400;
        $this->writeFile($data);

        $this->assertNull($store->get());
    }

    public function testLegacyUnsignedFileIsAcceptedWithoutKey(): void
    {
        // Tanpa kunci (store dipakai di luar Oauth2) tidak ada yang bisa
        // ditandatangani; perilaku lama dipertahankan agar tidak memutus
        // pemakaian yang sudah ada.
        $this->writeFile(['token' => 'tok-lama', 'expires_at' => time() + 3600]);

        $store = new FileTokenStore($this->path());

        $this->assertSame('tok-lama', $store->get()['token']);
    }

    public function testFileModeIsRestrictedToOwner(): void
    {
        $this->requirePosix('Bit permission POSIX tidak berlaku di Windows.');

        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');
        $store->set('tok-1', time() + 3600);

        clearstatcache(true, $this->path());

        $this->assertSame(0600, fileperms($this->path()) & 0777);
    }

    public function testGroupOrWorldReadableFileIsIgnored(): void
    {
        $this->requirePosix('Bit permission POSIX tidak berlaku di Windows.');

        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');
        $store->set('tok-1', time() + 3600);

        chmod($this->path(), 0644);
        clearstatcache(true, $this->path());

        $this->assertNull($store->get());
    }

    public function testNoTemporaryFileIsLeftBehind(): void
    {
        $store = new FileTokenStore($this->path(), 'kunci-aplikasi');
        $store->set('tok-1', time() + 3600);

        $this->assertSame([], glob($this->dir . DIRECTORY_SEPARATOR . '*.tmp') ?: []);
    }

    public function testPermissionVerificationRejectsFilesystemThatIgnoresChmod(): void
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            $this->markTestSkipped('Simulasi ini hanya berlaku di Windows, tempat chmod() tidak mengubah mode file.');
        }

        // Bug yang ditutup: kode lama memanggil chmod() dan menganggapnya
        // berhasil. Di Windows hasilnya 0666, sehingga token tertulis dengan
        // permission terbuka tanpa ada yang tahu.
        $store = new PosixFileTokenStore($this->path(), 'kunci-aplikasi');

        try {
            $store->set('tok-1', time() + 3600);
            $this->fail('chmod() yang tidak berefek seharusnya terdeteksi.');
        } catch (SSEnvException $e) {
            $this->assertStringContainsString('instead of 0600', $e->getMessage());
        }

        // Kredensial tidak boleh tertinggal dalam keadaan apa pun.
        $this->assertSame([], glob($this->dir . DIRECTORY_SEPARATOR . '*.tmp') ?: []);
        $this->assertFileDoesNotExist($this->path());
    }

    public function testOpenFileIsIgnoredOnPosixFilesystem(): void
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            $this->markTestSkipped('Simulasi ini hanya berlaku di Windows.');
        }

        $this->writeFile(['token' => 'tok-1', 'expires_at' => time() + 3600]);

        $store = new PosixFileTokenStore($this->path(), 'kunci-aplikasi');

        // Mode file di Windows selalu 0666, jadi bila platform dianggap POSIX
        // file itu terbuka untuk semua pengguna dan isinya tidak dipercaya.
        $this->assertNull($store->get());
    }

    public function testOauth2SignsTheStoreAutomatically(): void
    {
        $store = new FileTokenStore($this->path());

        $oauth2 = (new FakeOauth2([
            'environment' => 'staging',
            'organization_id' => 'org-1',
            'client_id' => 'client-1',
            'client_secret' => 'secret-1',
            'token_store' => $store,
        ]))->useClient($this->clientWithToken('tok-1'));

        $this->assertSame('tok-1', $oauth2->getToken());

        // Kunci sudah diisi Oauth2, jadi store yang sama membaca tokennya.
        $this->assertSame('tok-1', $store->get()['token']);

        // File yang ditulis pihak lain tetap ditolak.
        $this->writeFile(['token' => 'token-organisasi-lain', 'expires_at' => time() + 3600]);
        $this->assertNull($store->get());
    }

    public function testEmptySigningKeyIsTreatedAsNoKey(): void
    {
        $store = new FileTokenStore($this->path(), '');

        $this->writeFile(['token' => 'tok-lama', 'expires_at' => time() + 3600]);

        $this->assertSame('tok-lama', $store->get()['token']);
    }

    public function testUnwritableDirectoryFailsLoudly(): void
    {
        // Path yang tidak bisa dibuat: induknya berupa file biasa.
        $blocked = $this->dir . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . 'token.json';
        file_put_contents($this->dir . DIRECTORY_SEPARATOR . 'file', 'x');

        $store = new FileTokenStore($blocked);

        $this->expectException(SSEnvException::class);

        $store->set('tok-1', time() + 3600);
    }

    private function path(): string
    {
        return $this->dir . DIRECTORY_SEPARATOR . 'token.json';
    }

    private function readFile(): array
    {
        return json_decode((string) file_get_contents($this->path()), true);
    }

    private function readExpiresAt(): int
    {
        return (int) $this->readFile()['expires_at'];
    }

    private function writeFile(array $data): void
    {
        file_put_contents($this->path(), json_encode($data));

        // Mode 0600 supaya test benar-benar menguji tanda tangan, bukan
        // penolakan karena permission file terbuka.
        @chmod($this->path(), 0600);
    }

    private function requirePosix(string $reason): void
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->markTestSkipped($reason);
        }
    }

    private function clientWithToken(string $token): Client
    {
        $mock = new MockHandler([
            new PsrResponse(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => $token,
                'expires_in' => 3600,
            ])),
        ]);

        return new Client(['handler' => HandlerStack::create($mock)]);
    }
}

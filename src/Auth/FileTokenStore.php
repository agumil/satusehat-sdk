<?php
namespace agumil\SatuSehatSDK\Auth;

use agumil\SatuSehatSDK\Exception\SSEnvException;

/**
 * Store berbasis file untuk dipakai lintas proses (PHP-FPM, CLI worker).
 *
 * Token disimpan sebagai JSON dan ditulis secara atomik (tulis ke file
 * sementara lalu rename), sehingga proses lain tidak pernah membaca file
 * setengah jadi. Refresh dilakukan di bawah flock eksklusif agar hanya satu
 * proses yang meminta token baru saat token kedaluwarsa.
 *
 * Dua perlindungan yang dijaga di sini:
 *
 * 1. **Kerahasiaan** — file ditulis dengan permission 0600 dan mode hasilnya
 *    diverifikasi, bukan hanya diasumsikan dari nilai balik `chmod()`.
 * 2. **Integritas** — bila kunci penanda tersedia (diisi `Oauth2` dengan
 *    `client_secret`), isi file ditandatangani HMAC. File token yang ditulis
 *    pihak lain ditolak, sehingga token organisasi lain tidak bisa dipakai
 *    untuk mengarahkan data klinis ke organisasi yang salah.
 *
 * Catatan Windows: NTFS tidak mengenal bit permission POSIX. `chmod()` di sana
 * hanya mengatur flag read-only dan `fileperms()` selalu melaporkan 0666, jadi
 * mode file tidak bisa dijadikan bukti apa pun. Proteksi kerahasiaan di
 * Windows sepenuhnya bergantung pada ACL direktori penyimpanan — letakkan
 * store di direktori milik user aplikasi (bukan `C:\Windows\Temp` atau share
 * jaringan). Perlindungan integritas (HMAC) tetap berlaku di semua platform.
 */
class FileTokenStore implements KeyedTokenStoreInterface
{
    /** Mode file yang dianggap aman: hanya pemilik yang boleh baca/tulis. */
    const SAFE_MODE = 0600;

    /** Bit grup dan other; bila ada yang menyala, file dianggap terbuka. */
    const GROUP_OTHER_BITS = 0077;

    private string $path;

    private ?string $signing_key = null;

    public function __construct(string $path, ?string $signing_key = null)
    {
        $this->path = $path;
        $this->signing_key = $signing_key === '' ? null : $signing_key;
    }

    public function setSigningKey(string $key): void
    {
        $this->signing_key = $key === '' ? null : $key;
    }

    public function get(): ?array
    {
        if (!is_file($this->path)) {
            return null;
        }

        // File token adalah kredensial. Bila permission-nya terbuka, isinya
        // sudah bisa dibaca pihak lain dan karenanya tidak layak dipercaya;
        // token akan diminta ulang lalu file ditimpa dengan mode yang benar.
        if (!$this->hasSafePermission($this->path)) {
            return null;
        }

        $raw = @file_get_contents($this->path);

        if ($raw === false) {
            return null;
        }

        $data = json_decode($raw, true);

        if (!is_array($data) || !isset($data['token'], $data['expires_at'])) {
            return null;
        }

        // File tanpa tanda tangan sah (atau buatan pihak lain) diperlakukan
        // seperti belum ada token: lebih baik satu request token tambahan
        // daripada memakai token yang tidak jelas asalnya.
        if (!$this->isAuthentic($data)) {
            return null;
        }

        return [
            'token' => (string) $data['token'],
            'expires_at' => (int) $data['expires_at'],
        ];
    }

    public function set(string $token, int $expiresAt): void
    {
        $this->ensureDirectory();

        $payload = [
            'token' => $token,
            'expires_at' => $expiresAt,
        ];

        if ($this->signing_key !== null) {
            $payload['mac'] = $this->sign($token, $expiresAt);
        }

        $encoded = json_encode($payload);
        $tmp = $this->path . '.' . bin2hex(random_bytes(6)) . '.tmp';

        // umask 0077 menutup celah antara pembuatan file dan chmod(): tanpa
        // ini file sementara sempat tercipta 0644 dan berisi token.
        $previous_umask = umask(0077);

        try {
            $written = file_put_contents($tmp, $encoded);
        } finally {
            umask($previous_umask);
        }

        if ($written === false) {
            throw new SSEnvException("Unable to write token store at {$this->path}.");
        }

        try {
            $this->restrictPermission($tmp);
        } catch (SSEnvException $e) {
            // Menulis kredensial dengan permission yang tidak diketahui lebih
            // berbahaya daripada gagal.
            @unlink($tmp);

            throw $e;
        }

        if (!rename($tmp, $this->path)) {
            @unlink($tmp);

            throw new SSEnvException("Unable to replace token store at {$this->path}.");
        }

        // rename() mempertahankan mode, tetapi hasil akhirnya tetap diperiksa:
        // yang dilindungi adalah file di $this->path, bukan file sementara.
        $this->restrictPermission($this->path);
    }

    public function delete(): void
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
    }

    public function refresh(callable $fetch): array
    {
        $this->ensureDirectory();

        $lock = fopen($this->path . '.lock', 'c');

        // Tanpa file kunci tetap jalan, hanya kehilangan jaminan single-flight.
        if ($lock === false) {
            return $fetch();
        }

        try {
            flock($lock, LOCK_EX);

            // Proses lain mungkin sudah memperbarui token selagi kita menunggu
            // kunci, jadi store dibaca ulang sebelum memanggil endpoint token.
            $cached = $this->get();

            if ($cached !== null && time() < $cached['expires_at']) {
                return $cached;
            }

            $data = $fetch();
            $this->set($data['token'], $data['expires_at']);

            return $data;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Menandatangani isi token dengan kunci milik aplikasi.
     *
     * `expires_at` ikut ditandatangani supaya penyerang tidak bisa memperpanjang
     * masa berlaku token lama hanya dengan mengubah angkanya.
     */
    private function sign(string $token, int $expiresAt): string
    {
        return hash_hmac('sha256', $token . "\n" . $expiresAt, (string) $this->signing_key);
    }

    private function isAuthentic(array $data): bool
    {
        // Mode tanpa kunci hanya terjadi bila store dipakai di luar `Oauth2`,
        // yang tidak punya cara mengisi kunci. Dokumentasi menandainya.
        if ($this->signing_key === null) {
            return true;
        }

        if (!isset($data['mac']) || !is_string($data['mac'])) {
            return false;
        }

        return hash_equals($this->sign((string) $data['token'], (int) $data['expires_at']), $data['mac']);
    }

    /**
     * Memastikan file hanya bisa dibaca pemilik proses.
     *
     * `chmod()` mengembalikan true walau filesystem tidak mendukung permission
     * POSIX (FAT/vfat, beberapa share jaringan), jadi hasilnya diperiksa lewat
     * `fileperms()` dan bukan dipercaya begitu saja.
     */
    private function restrictPermission(string $file): void
    {
        if (!$this->isPosixFilesystem()) {
            // Lihat catatan kelas: mode POSIX tidak berlaku di Windows.
            return;
        }

        if (!@chmod($file, self::SAFE_MODE)) {
            throw new SSEnvException("Unable to restrict permission of token store at {$file} to 0600.");
        }

        clearstatcache(true, $file);
        $mode = @fileperms($file);

        if ($mode === false || ($mode & 0777) !== self::SAFE_MODE) {
            throw new SSEnvException(sprintf(
                'Token store at %s has mode %04o instead of 0600; the filesystem does not support the permission change.',
                $file,
                $mode === false ? 0 : ($mode & 0777)
            ));
        }
    }

    private function hasSafePermission(string $file): bool
    {
        if (!$this->isPosixFilesystem()) {
            return true;
        }

        clearstatcache(true, $file);
        $mode = @fileperms($file);

        if ($mode === false) {
            return false;
        }

        return ($mode & self::GROUP_OTHER_BITS) === 0;
    }

    /**
     * Apakah platform ini punya bit permission POSIX yang bisa dipercaya.
     *
     * Method terpisah (bukan `DIRECTORY_SEPARATOR === '\\'` yang ditulis
     * langsung) supaya jalur verifikasi bisa diuji di Windows: di sana chmod()
     * selalu "berhasil" tanpa mengubah mode, dan itulah kondisi yang harus
     * ditolak oleh `restrictPermission()`.
     */
    protected function isPosixFilesystem(): bool
    {
        return DIRECTORY_SEPARATOR !== '\\';
    }

    private function ensureDirectory(): void
    {
        $dir = dirname($this->path);

        // mkdir() memunculkan warning bila path tidak bisa dibuat (mis. salah
        // satu komponen induknya berupa file). Kegagalan itu sudah dilaporkan
        // lewat SSEnvException di bawah, jadi warning-nya tidak perlu ikut
        // naik ke error handler aplikasi.
        if ($dir !== '' && $dir !== '.' && !is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
            throw new SSEnvException("Unable to create token store directory {$dir}.");
        }
    }
}

<?php
namespace agumil\SatuSehatSDK\KYC;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\KYC\KycInterface;
use agumil\SatuSehatSDK\Response\Response;

class Kyc extends Base implements KycInterface
{
    /** Metode verifikasi KYC yang datanya cukup NIK dan nama. */
    const METHOD_REQUEST_PER_NIK = 'request_per_nik';

    /**
     * Menukar pesan terenkripsi milik faskes menjadi URL/token sesi KYC.
     *
     * Body dikirim apa adanya sebagai `text/plain`, bukan JSON: isinya
     * ciphertext yang dihasilkan proses enkripsi faskes (kunci dari Kemkes),
     * dan server menolak bila Content-Type-nya `application/json`.
     *
     * Respons juga masih berupa pesan terenkripsi. SDK tidak mendekripsinya
     * karena kuncinya hanya dipegang faskes; ambil `url_token` di sisi
     * aplikasi lalu pakai untuk `generateChallengeCode()`.
     *
     * @param string $encryptedMessage ciphertext lengkap, termasuk header
     *                                  `-----BEGIN ENCRYPTED MESSAGE-----`
     */
    public function generateUrl(string $encryptedMessage)
    {
        ValidatorHelper::required('encrypted_message', $encryptedMessage);

        $url = $this->base_url . 'generate-url';

        return new Response($this->httpRequest()->post($url, $encryptedMessage, [
            'Content-Type' => 'text/plain',
        ]));
    }

    /**
     * Meminta challenge code untuk satu permintaan KYC.
     *
     * @param array  $params   `metadata.method` dan `data` (lihat dokumentasi
     *                         KYC Kemkes untuk field tiap metode)
     * @param string $urlToken `url_token` hasil `generateUrl()`
     * @param bool   $debugMode kirim `X-Debug-Mode: 1` agar challenge code
     *                          dikembalikan langsung tanpa widget KYC. Hanya
     *                          untuk development/staging: mode ini melewati
     *                          verifikasi yang seharusnya dilakukan pasien,
     *                          sehingga ditolak di sini bila environment-nya
     *                          production — bukan hanya mengandalkan penolakan
     *                          server.
     *
     * @throws SSEnvException bila $debugMode diminta di production
     */
    public function generateChallengeCode(array $params, string $urlToken, bool $debugMode = false)
    {
        ValidatorHelper::required('url_token', $urlToken);
        $this->validateChallengePayload($params);

        if ($debugMode && $this->environment === 'production') {
            throw new SSEnvException(
                "KYC - 'X-Debug-Mode' is only available in development or staging. It bypasses patient verification and must not be sent to the production endpoint."
            );
        }

        $headers = ['X-Frame-Token' => $urlToken];

        if ($debugMode) {
            $headers['X-Debug-Mode'] = '1';
        }

        $url = $this->base_url . 'challenge-code';

        return new Response($this->httpRequest()->post($url, $params, $headers));
    }

    /**
     * Pintasan `generateChallengeCode()` untuk metode `request_per_nik`.
     *
     * NIK divalidasi lebih dulu supaya salah ketik tidak berujung pada
     * challenge code untuk orang lain — respons server memuat `ihs_number`
     * milik NIK yang dikirim.
     */
    public function requestPerNik(string $nik, string $name, string $urlToken, bool $debugMode = false)
    {
        ValidatorHelper::nik('nik', $nik);
        ValidatorHelper::required('name', $name);

        return $this->generateChallengeCode([
            'metadata' => ['method' => self::METHOD_REQUEST_PER_NIK],
            'data' => [
                'nik' => $nik,
                'name' => $name,
            ],
        ], $urlToken, $debugMode);
    }

    /**
     * Struktur payload diperiksa, tetapi daftar nilai `method` sengaja tidak
     * dibatasi: Kemkes menambah metode dari waktu ke waktu dan SDK yang
     * menolak metode baru akan menghalangi pemakaian yang sah.
     */
    private function validateChallengePayload(array $params): void
    {
        if (!isset($params['metadata']) || !is_array($params['metadata'])) {
            throw new SSDataTypeException('metadata is required and must be an array containing at least method.');
        }

        ValidatorHelper::required('metadata.method', $params['metadata']['method'] ?? null);

        if (!isset($params['data']) || !is_array($params['data']) || $params['data'] === []) {
            throw new SSDataTypeException('data is required and must be a non-empty array.');
        }
    }
}

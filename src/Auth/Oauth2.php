<?php
namespace agumil\SatuSehatSDK\Auth;

use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Exception\SSOauth2Exception;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Response\Response;
use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Oauth2
{
    /**
     * Batas waktu endpoint token, terpisah dari timeout request FHIR.
     *
     * Endpoint token dipanggil paling depan pada setiap proses yang belum punya
     * token, jadi server yang menggantung di sini menahan worker PHP-FPM tanpa
     * batas bila timeout tidak dipasang (Guzzle menunggu selamanya).
     */
    const DEFAULT_TIMEOUT = 15;

    const DEFAULT_CONNECT_TIMEOUT = 5;

    private $base_url;

    private $organization_id;

    private $client_id;

    private $client_secret;

    private $token = null;

    private $token_expired_at = 0;

    // Opt-in
    private $auto_refresh_token = false;

    private TokenStoreInterface $token_store;

    private int $timeout = self::DEFAULT_TIMEOUT;

    private int $connect_timeout = self::DEFAULT_CONNECT_TIMEOUT;

    private $event_listener = [
        'token_changed' => []
    ];

    public function __construct(array $config = [])
    {
        if (EnvHelper::isDevelopment() || ($config['environment'] ?? null) === 'development') {
            $this->base_url = Endpoint::DEV_OAUTH2;
        } elseif (EnvHelper::isStaging() || ($config['environment'] ?? null) === 'staging') {
            $this->base_url = Endpoint::STG_OAUTH2;
        } elseif (EnvHelper::isProduction() || ($config['environment'] ?? null) === 'production') {
            $this->base_url = Endpoint::PROD_OAUTH2;
        } else {
            $env = EnvHelper::getEnvironment();
            throw new SSEnvException("Oauth2 - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is must be 'development', 'staging' or 'production'.");
        }

        if (isset($config['organization_id'])) {
            $this->organization_id = $config['organization_id'];
        } else {
            $this->organization_id = EnvHelper::getOrganizationId();
        }

        // if (empty($this->organization_id)) {
        //     $env_organizationid = EnvHelper::ORGANIZATION_ID;
        //     throw new SSEnvException("Oauth2 - Environment '{$env_organizationid}' OR Configuration 'organization_id' must be provided.");
        // }

        if (isset($config['client_id'])) {
            $this->client_id = $config['client_id'];
        } else {
            $this->client_id = EnvHelper::getClientId();
        }

        if (empty($this->client_id)) {
            $env_clientid = EnvHelper::CLIENT_ID;
            throw new SSEnvException("Oauth2 - Environment '{$env_clientid}' OR Configuration 'client_id' must be provided.");
        }

        if (isset($config['client_secret'])) {
            $this->client_secret = $config['client_secret'];
        } else {
            $this->client_secret = EnvHelper::getClientSecret();
        }

        if (empty($this->client_secret)) {
            $env_clientsecret = EnvHelper::CLIENT_SECRET;
            throw new SSEnvException("Oauth2 - Environment '{$env_clientsecret}' OR Configuration 'client_secret' must be provided.");
        }

        if (isset($config['token'])) {
            $this->token = $config['token'];
        }

        if (isset($config['token_expired_at'])) {
            $this->token_expired_at = $config['token_expired_at'];
        }

        if (isset($config['auto_refresh_token'])) {
            $this->auto_refresh_token = $config['auto_refresh_token'];
        }

        // Default tetap per-instance (perilaku lama). Isi dengan FileTokenStore
        // bila token perlu dipakai bersama antar proses.
        $token_store = $config['token_store'] ?? new InMemoryTokenStore();

        if (!($token_store instanceof TokenStoreInterface)) {
            throw new SSEnvException('Oauth2 - Configuration \'token_store\' must implement ' . TokenStoreInterface::class . '.');
        }

        $this->token_store = $token_store;

        // Store yang menyimpan token ke disk menandatangani isinya dengan
        // client_secret, sehingga file token yang ditulis pihak lain (mis.
        // token organisasi lain) ditolak alih-alih dipakai.
        if ($this->token_store instanceof KeyedTokenStoreInterface) {
            $this->token_store->setSigningKey($this->client_secret);
        }

        if (isset($config['timeout'])) {
            ValidatorHelper::positiveInt('timeout', $config['timeout']);

            $this->timeout = (int) $config['timeout'];
        }

        if (isset($config['connect_timeout'])) {
            ValidatorHelper::positiveInt('connect_timeout', $config['connect_timeout']);

            $this->connect_timeout = (int) $config['connect_timeout'];
        }

        if ($this->token !== null && $this->token_expired_at > 0) {
            $this->token_store->set($this->token, (int) $this->token_expired_at);
        }
    }

    public function getContent()
    {
        return $this->generateToken()->getContent();
    }

    public function getToken(bool $refreshToken = false)
    {
        if ($refreshToken) {
            $this->adoptToken(null, 0);
            $this->token_store->delete();
        }

        $now = (new DateTime('now'))->getTimestamp();

        if ($this->token !== null && $now < $this->token_expired_at) {
            return $this->token;
        }

        // Token mungkin sudah diperbarui proses lain; store lintas proses
        // diperiksa lebih dulu supaya tidak ada request token yang sia-sia.
        $cached = $this->token_store->get();

        if ($cached !== null && $now < $cached['expires_at']) {
            $this->adoptToken($cached['token'], $cached['expires_at']);

            return $this->token;
        }

        // refresh() mengunci store selama request token berjalan, sehingga
        // beberapa proses yang bersamaan tetap hanya menghasilkan satu request.
        $fresh = $this->token_store->refresh(function () {
            return $this->requestNewToken();
        });

        $this->adoptToken($fresh['token'], $fresh['expires_at']);

        return $this->token;
    }

    /**
     * Meminta access token baru ke SATUSEHAT.
     *
     * @return array{token: string, expires_at: int}
     */
    protected function requestNewToken(): array
    {
        $response = $this->generateToken();

        if ($response->getHttpStatus() !== $response::STATUS_2XX) {
            $this->adoptToken(null, 0);
            $this->token_store->delete();

            // Kode HTTP disertakan karena sebelumnya hanya isi body yang
            // dilaporkan, dan body 5xx dari gateway sering berupa HTML panjang
            // yang menyembunyikan penyebab sebenarnya.
            throw new SSOauth2Exception(
                'Oauth2 - Token request failed with HTTP ' . $response->getHttpCode()
                . ': ' . $this->scrubSecret($this->excerpt($response->getContent()))
            );
        }

        $body = json_decode($response->getContent());

        // 2xx tanpa access_token/expires_in (mis. halaman HTML dari proxy)
        // harus gagal di sini, bukan menjadi token null di tengah request.
        if (!is_object($body) || !isset($body->access_token) || !isset($body->expires_in)) {
            $this->adoptToken(null, 0);
            $this->token_store->delete();

            throw new SSOauth2Exception('Oauth2 - Token response is not recognized: ' . $this->scrubSecret($this->excerpt($response->getContent())));
        }

        // Dikurangi 60 detik agar token tidak kedaluwarsa di tengah request,
        // dan tidak pernah negatif bila server memberi expires_in < 60.
        $lifetime = max(0, (int) $body->expires_in - 60);
        $expiredAt = (new DateTime('now'))->add(new DateInterval("PT{$lifetime}S"))->getTimestamp();

        return [
            'token' => (string) $body->access_token,
            'expires_at' => $expiredAt,
        ];
    }

    /**
     * Menetapkan token sekaligus memicu `token_changed` hanya bila nilainya
     * benar-benar berubah.
     */
    private function adoptToken(?string $token, int $expiredAt): void
    {
        if ($this->token === $token && $this->token_expired_at === $expiredAt) {
            return;
        }

        $this->token = $token;
        $this->token_expired_at = $expiredAt;
        $this->fireTokenChanged();
    }

    private function fireTokenChanged()
    {
        foreach ($this->event_listener['token_changed'] as $callback) {
            $callback($this->token, $this->token_expired_at);
        }
    }

    /**
     * Seam untuk pengujian: subclass dapat mengganti client dengan handler
     * tiruan tanpa menyentuh jaringan.
     */
    protected function createClient(): Client
    {
        return new Client();
    }

    /**
     * Meminta token ke endpoint OAuth2 SATUSEHAT.
     *
     * Dua hal yang sebelumnya tidak ada dan berbahaya di produksi:
     *
     * 1. Tidak ada timeout sama sekali, sehingga server yang menggantung
     *    menahan proses PHP tanpa batas.
     * 2. Hanya `ClientException` yang ditangkap, padahal 5xx melempar
     *    `ServerException` dan kegagalan koneksi melempar `ConnectException`.
     *    Keduanya bocor ke aplikasi pemanggil sebagai exception Guzzle, bukan
     *    `SSOauth2Exception` seperti yang dijanjikan kontrak SDK.
     *
     * @throws SSOauth2Exception bila tidak ada respons yang bisa dibaca
     */
    private function generateToken()
    {
        $url = StrHelper::appendSlash($this->base_url) . 'accesstoken';

        $options = [
            'query' => ['grant_type' => 'client_credentials'],
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
            ],
            'timeout' => $this->timeout,
            'connect_timeout' => $this->connect_timeout,
        ];

        try {
            $response = $this->createClient()->post($url, $options);
        } catch (GuzzleException $e) {
            // 4xx/5xx tetap membawa respons: diteruskan apa adanya supaya
            // requestNewToken() bisa melaporkan isi OperationOutcome server.
            //
            // GuzzleException dipakai, bukan ClientException: `ConnectException`
            // dan `ServerException` tidak mewarisi ClientException, sehingga
            // keduanya dulu bocor ke aplikasi sebagai exception Guzzle.
            if ($e instanceof RequestException && $e->getResponse() !== null) {
                $response = $e->getResponse();
            } else {
                // Tanpa respons berarti DNS gagal, TLS ditolak, atau timeout.
                // Pesan Guzzle bisa memuat URL, jadi secret tetap disaring
                // sebagai langkah defensif sebelum masuk ke log aplikasi.
                throw new SSOauth2Exception(
                    'Oauth2 - Unable to reach token endpoint: ' . $this->scrubSecret($e->getMessage()),
                    0,
                    $e
                );
            }
        }

        return new Response($response);
    }

    /**
     * Menghapus client_secret dari teks yang akan dilempar sebagai exception.
     *
     * Guzzle normalnya hanya menyertakan URI, bukan body, tetapi pesan error
     * dari lapisan bawah (cURL, proxy) tidak dijamin begitu.
     */
    private function scrubSecret(string $message): string
    {
        if (!is_string($this->client_secret) || $this->client_secret === '') {
            return $message;
        }

        return str_replace($this->client_secret, '[REDACTED]', $message);
    }

    /**
     * Memotong body respons agar pesan exception tetap terbaca. Halaman HTML
     * dari proxy bisa mencapai puluhan kilobita dan menenggelamkan pesan asli.
     */
    private function excerpt(string $body, int $limit = 500): string
    {
        $body = trim($body);

        return strlen($body) <= $limit ? $body : substr($body, 0, $limit) . '...';
    }

    public function getOrganizationId()
    {
        return $this->organization_id;
    }

    public function isTokenAutoRefresh()
    {
        return $this->auto_refresh_token;
    }

    public function addEventListener(string $type, callable $callback) {
        $this->event_listener[$type][] = $callback;
        return $this;
    }
}

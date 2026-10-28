<?php
namespace agumil\SatuSehatSDK\Request;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Connection;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\PhiRedactor;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class HttpRequest
{
    /**
     * Status yang layak diulang: rate limit dan gangguan sementara di sisi
     * server. 4xx lain (400/401/403/404) tidak akan berubah bila diulang.
     */
    const RETRYABLE_STATUS = [429, 500, 502, 503, 504];

    /** Batas atas jeda dari header `Retry-After`. */
    const RETRY_AFTER_MAX_MS = 60000;

    private $token;
    private $oauth2;

    protected int $timeout = 60;

    private ?LoggerInterface $logger = null;

    private int $retry_attempts = 3;

    private int $retry_base_delay_ms = 200;

    private int $retry_max_delay_ms = 5000;

    /**
     * Hanya method idempoten yang diulang secara default. Mengulang POST bisa
     * membuat resource tercatat dua kali bila sebenarnya request pertama
     * berhasil tetapi responsnya hilang di tengah jalan.
     */
    private array $retry_methods = ['GET', 'PUT', 'DELETE'];

    public function __construct(Oauth2 $oauth2, Connection | array $config = [])
    {
        if (!($config instanceof Connection)) {
            if (isset($config['timeout'])) {
                ValidatorHelper::unsignedInt('timeout', $config['timeout']);

                $this->timeout = (int) $config['timeout'];
            }

            // Logger opsional (PSR-3). Tanpa logger, SDK tidak menulis log apa pun.
            if (isset($config['logger'])) {
                $this->logger = $config['logger'];
            }

            if (isset($config['retry_attempts'])) {
                ValidatorHelper::positiveInt('retry_attempts', $config['retry_attempts']);

                $this->retry_attempts = (int) $config['retry_attempts'];
            }

            if (isset($config['retry_base_delay_ms'])) {
                ValidatorHelper::unsignedInt('retry_base_delay_ms', $config['retry_base_delay_ms']);

                $this->retry_base_delay_ms = (int) $config['retry_base_delay_ms'];
            }

            if (isset($config['retry_max_delay_ms'])) {
                ValidatorHelper::positiveInt('retry_max_delay_ms', $config['retry_max_delay_ms']);

                $this->retry_max_delay_ms = (int) $config['retry_max_delay_ms'];
            }

            if (isset($config['retry_methods'])) {
                if (!is_array($config['retry_methods'])) {
                    throw new SSDataTypeException('retry_methods must be an array of HTTP methods, e.g. [\'GET\', \'PUT\'].');
                }

                $this->retry_methods = array_map('strtoupper', $config['retry_methods']);
            }
        } else {
            $this->timeout = $config->getTimeout();
        }

        $this->oauth2 = $oauth2;
        $this->token = $oauth2->getToken();
    }

    private function request(string $method, string $url, $payloads = null, bool $retry = true, array $headers = [])
    {
        $attempt = 1;

        while (true) {
            $guzzle = $this->createClient();
            $options = $this->initializeOptions($method, $payloads, $headers);
            $started_at = microtime(true);

            try {
                $response = $guzzle->request($method, $url, $options);
                $this->logExchange($method, $url, $response->getStatusCode(), $payloads, $started_at);
            } catch (ConnectException $e) {
                $this->logExchange($method, $url, null, $payloads, $started_at, $e->getMessage());
                $response = $e->getHandlerContext();
            } catch (RequestException $e) {
                // RequestException menaungi ClientException (4xx), ServerException
                // (5xx), dan kegagalan transfer lain. Kontrak SDK adalah "selalu
                // mengembalikan Response", jadi semuanya harus ditangkap di sini —
                // bukan dibiarkan bocor ke aplikasi pemanggil.
                $error_response = $e->getResponse();

                if ($error_response === null) {
                    $this->logExchange($method, $url, null, $payloads, $started_at, $e->getMessage());
                    $response = $e->getHandlerContext();
                } elseif (
                    $error_response->getStatusCode() === 401 &&
                    $this->oauth2->isTokenAutoRefresh() &&
                    $retry &&
                    in_array('invalid-access-token', $this->readIssueCodes($error_response), true)
                ) {
                    $this->logExchange($method, $url, 401, $payloads, $started_at, 'access token invalid, refreshing');
                    $this->token = $this->oauth2->getToken(true);

                    return $this->request($method, $url, $payloads, false, $headers);
                } else {
                    $this->logExchange($method, $url, $error_response->getStatusCode(), $payloads, $started_at);
                    $response = $error_response;
                }
            }

            if (!$this->shouldRetry($method, $response, $attempt)) {
                return $response;
            }

            $delay_ms = $this->retryDelayMs($response, $attempt);
            $this->logRetry($method, $url, $response->getStatusCode(), $attempt, $delay_ms);

            usleep($delay_ms * 1000);
            $attempt++;
        }
    }

    private function shouldRetry(string $method, $response, int $attempt): bool
    {
        if ($attempt >= $this->retry_attempts) {
            return false;
        }

        if (!in_array($method, $this->retry_methods, true)) {
            return false;
        }

        // $response berupa array bila kegagalan terjadi sebelum ada respons
        // HTTP (mis. koneksi putus); kasus itu tidak diulang di sini.
        if (!($response instanceof ResponseInterface)) {
            return false;
        }

        return in_array($response->getStatusCode(), self::RETRYABLE_STATUS, true);
    }

    /**
     * Menghitung jeda sebelum percobaan berikutnya: hormati `Retry-After` bila
     * ada, jika tidak pakai backoff eksponensial dengan jitter.
     */
    private function retryDelayMs(ResponseInterface $response, int $attempt): int
    {
        $retry_after = trim($response->getHeaderLine('Retry-After'));

        if ($retry_after !== '') {
            // Retry-After eksplisit dari server dihormati sampai 60 detik;
            // batas ini hanya mencegah header aneh menahan proses terlalu lama.
            if (ctype_digit($retry_after)) {
                return min((int) $retry_after * 1000, self::RETRY_AFTER_MAX_MS);
            }

            $timestamp = strtotime($retry_after);

            if ($timestamp !== false) {
                return min(max(0, $timestamp - time()) * 1000, self::RETRY_AFTER_MAX_MS);
            }
        }

        $delay = $this->retry_base_delay_ms * (2 ** ($attempt - 1));
        // Jitter 0-20% agar beberapa proses tidak mengirim ulang pada saat sama.
        $delay += random_int(0, (int) ($delay * 0.2));

        return min($delay, $this->retry_max_delay_ms);
    }

    private function logRetry(string $method, string $url, int $status, int $attempt, int $delayMs): void
    {
        if ($this->logger === null) {
            return;
        }

        $this->logger->warning('SATUSEHAT request retried', [
            'method' => $method,
            'url' => PhiRedactor::redactUrl($url),
            'status' => $status,
            'attempt' => $attempt,
            'next_attempt' => $attempt + 1,
            'delay_ms' => $delayMs,
        ]);
    }

    /**
     * Mencatat satu pertukaran HTTP. Payload dan query string disaring lebih
     * dulu lewat PhiRedactor supaya data pasien tidak masuk ke log.
     */
    private function logExchange(string $method, string $url, ?int $status, $payloads, float $startedAt, ?string $note = null): void
    {
        if ($this->logger === null) {
            return;
        }

        $context = [
            'method' => $method,
            'url' => PhiRedactor::redactUrl($url),
            'status' => $status,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ];

        if (is_array($payloads) && $payloads !== []) {
            $context['payload'] = PhiRedactor::redact($payloads);
        } elseif (is_string($payloads)) {
            // Body mentah bisa memuat PHI; yang dicatat hanya ukurannya.
            $context['payload_bytes'] = strlen($payloads);
        } elseif (is_object($payloads)) {
            $context['payload_class'] = get_class($payloads);
        }

        if ($note !== null) {
            $context['note'] = $note;
        }

        if ($status === null || $status >= 500) {
            $this->logger->error('SATUSEHAT request failed', $context);
        } elseif ($status >= 400) {
            $this->logger->warning('SATUSEHAT request rejected', $context);
        } else {
            $this->logger->debug('SATUSEHAT request', $context);
        }
    }

    /**
     * Seam untuk pengujian: subclass dapat mengganti client dengan handler tiruan
     * tanpa menyentuh jaringan, dan nanti bisa dipakai untuk connection reuse.
     */
    protected function createClient(): Client
    {
        return new Client();
    }

    /**
     * Mengambil seluruh kode issue dari body error tanpa menghabiskan stream.
     *
     * Body dibaca sekali lalu stream di-rewind, karena `Response` membaca body
     * lewat getContents() saat konstruksi. Tanpa rewind, pesan error asli dari
     * SATUSEHAT hilang dan `getErrorMessages()` mengembalikan array kosong.
     *
     * `issue` pada OperationOutcome adalah array (0..*), tetapi ada server yang
     * mengirim objek tunggal — keduanya diterima. Bentuk array inilah yang
     * membuat pemeriksaan lama `->issue->code` selalu null sehingga auto-refresh
     * token tidak pernah terpicu.
     */
    private function readIssueCodes(ResponseInterface $response): array
    {
        $stream = $response->getBody();
        $seekable = $stream->isSeekable();

        if ($seekable) {
            $stream->rewind();
        }

        $body = $stream->getContents();

        if ($seekable) {
            $stream->rewind();
        }

        // Body error bisa saja bukan JSON (mis. halaman HTML dari gateway),
        // jadi hasil decode tidak boleh diakses tanpa pemeriksaan.
        $decoded = json_decode($body, true);

        if (!is_array($decoded) || !isset($decoded['issue'])) {
            return [];
        }

        $issues = $decoded['issue'];

        if (isset($issues['code'])) {
            return [(string) $issues['code']];
        }

        if (!is_array($issues)) {
            return [];
        }

        $codes = [];

        foreach ($issues as $issue) {
            if (is_array($issue) && isset($issue['code'])) {
                $codes[] = (string) $issue['code'];
            }
        }

        return $codes;
    }

    private function initializeOptions($method, $payloads, array $headers = [])
    {
        $options['headers'] = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $options['timeout'] = $this->timeout;

        // Header tambahan per-request (mis. `X-Frame-Token` pada KYC). Nama
        // header tidak peka huruf besar/kecil, jadi varian lama dibuang lebih
        // dulu agar `content-type` tidak terkirim dua kali bersama `Content-Type`.
        foreach ($headers as $name => $value) {
            foreach (array_keys($options['headers']) as $existing) {
                if (strcasecmp($existing, $name) === 0) {
                    unset($options['headers'][$existing]);
                }
            }

            $options['headers'][$name] = $value;
        }

        if ($payloads === null || $payloads === [] || $payloads === '') {
            return $options;
        }

        // Tipe payload dideteksi eksplisit. Sebelumnya `$payloads->build()`
        // dibungkus `catch (\Throwable)` sebagai cara membedakan builder dari
        // array; akibatnya exception validasi dari builder ikut tertelan dan
        // SDK tetap mengirim body `{}` (objek ber-properti private di-encode
        // jadi objek kosong). Sekarang exception builder dibiarkan naik supaya
        // gagal cepat di sisi pemanggil, bukan jadi 400 yang membingungkan.
        if (is_array($payloads)) {
            // GET tidak punya body; array pada GET adalah query string.
            if ($method === 'GET') {
                $options['query'] = $payloads;
            } else {
                $options['json'] = $payloads;
            }

            return $options;
        }

        if (is_object($payloads) && method_exists($payloads, 'build')) {
            $options['json'] = $payloads->build();

            return $options;
        }

        if (is_string($payloads)) {
            $options['body'] = $payloads;
        } else {
            $options['body'] = json_encode($payloads);
        }

        return $options;
    }

    public function get(string $url, array $payloads = [], array $headers = [])
    {
        return $this->request('GET', $url, $payloads, true, $headers);
    }

    public function post(string $url, $payloads = null, array $headers = [])
    {
        return $this->request('POST', $url, $payloads, true, $headers);
    }

    public function put(string $url, $payloads = null, array $headers = [])
    {
        return $this->request('PUT', $url, $payloads, true, $headers);
    }

    /**
     * PATCH dipakai SATUSEHAT untuk update parsial (JSON Patch, RFC 6902).
     * `Patient` hanya mendukung PATCH, tidak ada PUT.
     */
    public function patch(string $url, $payloads = null, array $headers = [])
    {
        return $this->request('PATCH', $url, $payloads, true, $headers);
    }

    public function delete(string $url, $payloads = null, array $headers = [])
    {
        return $this->request('DELETE', $url, $payloads, true, $headers);
    }
}

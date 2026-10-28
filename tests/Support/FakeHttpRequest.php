<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use agumil\SatuSehatSDK\Request\HttpRequest;
use GuzzleHttp\Psr7\Response;

/**
 * `HttpRequest` yang hanya merekam bentuk request.
 *
 * Konstruktor induk sengaja tidak dipanggil: konstruktor asli meminta access
 * token ke SATUSEHAT, sedangkan kelas ini dipakai untuk memeriksa header dan
 * URL tanpa jaringan.
 */
class FakeHttpRequest extends HttpRequest
{
    /** @var array<int, array{method: string, url: string, payloads: mixed, headers: array}> */
    public array $calls = [];

    private $response;

    public function __construct($response = null)
    {
        $this->response = $response ?? new Response(200, [], '{}');
    }

    public function get(string $url, array $payloads = [], array $headers = [])
    {
        $this->calls[] = ['method' => 'GET', 'url' => $url, 'payloads' => $payloads, 'headers' => $headers];

        return $this->response;
    }

    public function post(string $url, $payloads = null, array $headers = [])
    {
        $this->calls[] = ['method' => 'POST', 'url' => $url, 'payloads' => $payloads, 'headers' => $headers];

        return $this->response;
    }

    public function patch(string $url, $payloads = null, array $headers = [])
    {
        $this->calls[] = ['method' => 'PATCH', 'url' => $url, 'payloads' => $payloads, 'headers' => $headers];

        return $this->response;
    }
}

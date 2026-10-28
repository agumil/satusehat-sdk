<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use agumil\SatuSehatSDK\Request\HttpRequest;
use GuzzleHttp\Client;
use LogicException;

/**
 * `HttpRequest` dengan client Guzzle yang bisa diganti, untuk memeriksa apa
 * yang benar-benar dikirim dan dicatat tanpa jaringan.
 */
class TestableHttpRequest extends HttpRequest
{
    private ?Client $client = null;

    public function useClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    protected function createClient(): Client
    {
        if ($this->client === null) {
            throw new LogicException('TestableHttpRequest: client belum diatur lewat useClient().');
        }

        return $this->client;
    }
}

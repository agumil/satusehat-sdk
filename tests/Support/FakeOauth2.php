<?php
namespace agumil\SatuSehatSDK\Tests\Support;

use agumil\SatuSehatSDK\Auth\Oauth2;
use GuzzleHttp\Client;
use LogicException;

/**
 * `Oauth2` dengan client Guzzle yang bisa diganti, sehingga endpoint token
 * dapat diuji tanpa jaringan.
 */
class FakeOauth2 extends Oauth2
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
            throw new LogicException('FakeOauth2: client belum diatur lewat useClient().');
        }

        return $this->client;
    }
}

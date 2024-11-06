<?php
namespace agumil\SatuSehatSDK\Request;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Connection;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class HttpRequest
{
    private $token;
    private $oauth2;

    protected int $timeout = 60;

    public function __construct(Oauth2 $oauth2, Connection | array $config = [])
    {
        if (!($config instanceof Connection)) {
            if (isset($config['timeout'])) {
                ValidatorHelper::unsignedInt('timeout', $config['timeout']);

                $this->timeout = (int) $config['timeout'];
            }
        } else {
            $this->timeout = $config->getTimeout();
        }

        $this->oauth2 = $oauth2;
        $this->token = $oauth2->getToken();
    }

    private function request(string $method, string $url, $payloads = null, bool $retry = true)
    {
        $guzzle = new Client();
        $options = $this->initializeOptions($payloads);

        try {
            $response = $guzzle->request($method, $url, $options);
        } catch (ClientException $e) {
            if ($e->getCode() === 401 && $this->oauth2->isTokenAutoRefresh() && $retry) {
                $this->token = $this->oauth2->getToken(true);
                return $this->request($method, $url, $payloads, false);
            }
            $response = $e->getResponse();
        }

        return $response;
    }

    private function initializeOptions($payloads)
    {
        $options['headers'] = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $options['timeout'] = $this->timeout;

        if (!empty($payloads)) {
            if (is_string($payloads)) {
                $options['body'] = $payloads;
            } elseif (is_array($payloads)) {
                $options['json'] = $payloads;
            } else {
                $options['body'] = json_encode($payloads);
            }
        }

        return $options;
    }

    public function get(string $url, array $payloads = [])
    {
        return $this->request('GET', $url, $payloads);
    }

    public function post(string $url, $payloads = null)
    {
        return $this->request('POST', $url, $payloads);
    }

    public function put(string $url, $payloads = null)
    {
        return $this->request('PUT', $url, $payloads);
    }

    public function delete(string $url, $payloads = null)
    {
        return $this->request('DELETE', $url, $payloads);
    }
}

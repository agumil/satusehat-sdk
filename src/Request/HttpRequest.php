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

        $this->token = $oauth2->getToken();
    }

    public function get(string $url, array $payloads = [])
    {
        $guzzle = new Client();

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = 'Bearer ' . $this->token;
        $options['timeout'] = $this->timeout;

        if (!empty($payloads)) {
            $options['query'] = $payloads;
        }

        try {
            $response = $guzzle->get($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function post(string $url, $payloads = null)
    {
        $guzzle = new Client();

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = 'Bearer ' . $this->token;
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

        try {
            $response = $guzzle->post($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function put(string $url, $payloads = null)
    {
        $guzzle = new Client();

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = 'Bearer ' . $this->token;
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

        try {
            $response = $guzzle->put($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function delete(string $url, $payloads = null)
    {
        $guzzle = new Client();

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Authorization'] = 'Bearer ' . $this->token;
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

        try {
            $response = $guzzle->delete($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}

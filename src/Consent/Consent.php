<?php
namespace agumil\SatuSehatSDK\Consent;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Interface\Consent\ConsentInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Consent implements ConsentInterface
{
    private string $base_url;

    private Oauth2 $oauth2;

    private array $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        if (EnvHelper::isDevelopment() || @$config['environment'] === 'development') {
            $this->base_url = Endpoint::DEV_CONSENT;
        } elseif (EnvHelper::isStaging() || @$config['environment'] === 'staging') {
            $this->base_url = Endpoint::STG_CONSENT;
        } elseif (EnvHelper::isProduction() || @$config['environment'] === 'production') {
            $this->base_url = Endpoint::PROD_CONSENT;
        } else {
            $env = EnvHelper::ENV;
            throw new SSEnvException("Environment {$env} value is invalid or not exist. Valid environment value is one of 'development', 'staging', or 'production'.");
        }

        $this->oauth2 = $oauth2;
        $this->config = $config;
    }

    public function getConsent(array $params = [])
    {
        $url = StrHelper::appendSlash($this->base_url) . 'Consent';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function updateConsentRaw(string $id, $params)
    {
        $url = StrHelper::appendSlash($this->base_url) . "Consent/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

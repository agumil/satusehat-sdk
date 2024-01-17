<?php
namespace agumil\SatuSehatSDK\Consent;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Interface\Consent\ConsentInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Consent implements ConsentInterface
{
    private $base_url;

    private $oauth2;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        if (isset($config['base_url'])) {
            $this->base_url = $config['base_url'];
        } else {
            if (EnvHelper::isDevelopment()) {
                $this->base_url = Endpoint::DEV_CONSENT;
            } elseif (EnvHelper::isStaging()) {
                $this->base_url = Endpoint::STG_CONSENT;
            } elseif (EnvHelper::isProduction()) {
                $this->base_url = Endpoint::PROD_CONSENT;
            } else {
                $env = EnvHelper::ENV;
                throw new SSEnvException("Environment {$env} value is invalid or not exist. Valid environment value is one of 'development', 'staging', or 'production'.");
            }
        }

        $this->oauth2 = $oauth2;
    }

    public function getConsent(array $params = [])
    {
        $url = $this->base_url . 'Consent';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function updateConsentRaw(string $id, $params)
    {
        $url = $this->base_url . "Consent/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

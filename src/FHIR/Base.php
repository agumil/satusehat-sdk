<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;

class Base
{
    protected Oauth2 $oauth2;

    protected string $base_url;

    protected array $config;

    protected function __construct(Oauth2 $oauth2, array $config = [])
    {
        if (EnvHelper::isDevelopment() || @$config['environment'] === 'development') {
            $this->base_url = Endpoint::DEV_FHIR;
        } elseif (EnvHelper::isStaging() || @$config['environment'] === 'staging') {
            $this->base_url = Endpoint::STG_FHIR;
        } elseif (EnvHelper::isProduction() || @$config['environment'] === 'production') {
            $this->base_url = Endpoint::PROD_FHIR;
        } else {
            $env = EnvHelper::ENV;
            throw new SSEnvException("FHIR Client - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is one of 'development', 'staging', or 'production'.");
        }

        $this->oauth2 = $oauth2;
        $this->base_url = StrHelper::appendSlash($this->base_url);
        $this->config = $config;
    }
}

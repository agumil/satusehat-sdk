<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;

class Base
{
    protected $oauth2;
    protected $base_url;

    protected function __construct(Oauth2 $oauth2, array $config = [])
    {
        if (isset($config['base_url'])) {
            $this->base_url = $config['base_url'];
        } else {
            if (EnvHelper::isDevelopment() || $config['environment'] === 'development') {
                $this->base_url = Endpoint::DEV_FHIR;
            } elseif (EnvHelper::isStaging() || $config['environment'] === 'staging') {
                $this->base_url = Endpoint::STG_FHIR;
            } elseif (EnvHelper::isProduction() || $config['environment'] === 'production') {
                $this->base_url = Endpoint::PROD_FHIR;
            } else {
                $env = EnvHelper::ENV;
                throw new SSEnvException("FHIR Client - Environment '{$env}' OR Configuration 'environment' must be provided. Valid environment value is one of 'development', 'staging', or 'production'.");
            }
        }

        $this->oauth2 = $oauth2;
        $this->base_url = StrHelper::appendSlash($this->base_url);
    }
}

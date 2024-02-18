<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;

class Base
{
    protected Oauth2 $oauth2;

    protected string $environment;

    protected array $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        if (EnvHelper::isDevelopment() || @$config['environment'] === 'development') {
            $this->environment = 'development';
        } elseif (EnvHelper::isStaging() || @$config['environment'] === 'staging') {
            $this->environment = 'staging';
        } elseif (EnvHelper::isProduction() || @$config['environment'] === 'production') {
            $this->environment = 'production';
        } else {
            $env = EnvHelper::ENV;
            throw new SSEnvException("KFA Client - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is one of 'development', 'staging', or 'production'.");
        }

        $this->oauth2 = $oauth2;
        $this->environment = $config['environment'];
        $this->config = $config;
    }

    protected function baseURL(int $version)
    {
        switch ($this->environment) {
            case 'production':
                $base_url = $version == 1 ? Endpoint::PROD_KFA_V1 : Endpoint::PROD_KFA_V2;
                break;

            case 'staging':
                $base_url = $version == 1 ? Endpoint::STG_KFA_V1 : Endpoint::STG_KFA_V2;
                break;

            case 'development':
            default:
                $base_url = $version == 1 ? Endpoint::DEV_KFA_V1 : Endpoint::DEV_KFA_V2;
                break;
        }

        return $base_url;
    }
}

<?php
namespace agumil\SatuSehatSDK\KFA;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
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
            $env = EnvHelper::getEnvironment();
            throw new SSEnvException("KFA Client - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is must be 'development', 'staging' or 'production'.");
        }

        $this->oauth2 = $oauth2;
        // `environment` sudah ditentukan di blok di atas. Jangan ditimpa dari
        // $config lagi: bila nilainya hanya berasal dari .env, key itu tidak ada
        // dan properti bertipe string ini akan menerima null (TypeError).
        $this->config = $config;
    }

    protected function baseURL(int $version)
    {
        // Pemetaan environment -> versi API. Dokumentasi KFA memisahkan v1
        // (harga JKN), v2 (produk), dan v3 (ALKES) pada base URL berbeda.
        $endpoints = [
            'production' => [
                1 => Endpoint::PROD_KFA_V1,
                2 => Endpoint::PROD_KFA_V2,
                3 => Endpoint::PROD_KFA_V3,
            ],
            'staging' => [
                1 => Endpoint::STG_KFA_V1,
                2 => Endpoint::STG_KFA_V2,
                3 => Endpoint::STG_KFA_V3,
            ],
            'development' => [
                1 => Endpoint::DEV_KFA_V1,
                2 => Endpoint::DEV_KFA_V2,
                3 => Endpoint::DEV_KFA_V3,
            ],
        ];

        $environment = isset($endpoints[$this->environment]) ? $this->environment : 'development';

        if (!isset($endpoints[$environment][$version])) {
            throw new SSDataTypeException("KFA API version {$version} is not available.");
        }

        return $endpoints[$environment][$version];
    }
}

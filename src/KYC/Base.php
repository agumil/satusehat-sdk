<?php
namespace agumil\SatuSehatSDK\KYC;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Request\HttpRequest;

class Base
{
    /** Lingkungan yang punya base URL KYC sendiri. */
    const ENVIRONMENTS = ['development', 'staging', 'production'];

    protected Oauth2 $oauth2;

    protected string $environment;

    protected string $base_url;

    protected array $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        $this->environment = $this->resolveEnvironment($config);
        $this->base_url = StrHelper::appendSlash($this->resolveBaseUrl($this->environment));

        $this->oauth2 = $oauth2;
        $this->config = $config;
    }

    /**
     * `.env` didahulukan, sama seperti modul FHIR/Consent/KFA, supaya satu
     * `SATUSEHAT_ENV` berlaku untuk seluruh modul dalam satu aplikasi.
     */
    private function resolveEnvironment(array $config): string
    {
        if (EnvHelper::isDevelopment()) {
            return 'development';
        }

        if (EnvHelper::isStaging()) {
            return 'staging';
        }

        if (EnvHelper::isProduction()) {
            return 'production';
        }

        $environment = $config['environment'] ?? null;

        if (in_array($environment, self::ENVIRONMENTS, true)) {
            return $environment;
        }

        $env = EnvHelper::getEnvironment();

        throw new SSEnvException("KYC Client - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is must be 'development', 'staging' or 'production'.");
    }

    private function resolveBaseUrl(string $environment): string
    {
        $endpoints = [
            'development' => Endpoint::DEV_KYC,
            'staging' => Endpoint::STG_KYC,
            'production' => Endpoint::PROD_KYC,
        ];

        if (!isset($endpoints[$environment])) {
            throw new SSEnvException("KYC Client - Environment '{$environment}' is not supported.");
        }

        return $endpoints[$environment];
    }

    /**
     * Seam untuk pengujian: subclass dapat mengembalikan HttpRequest tiruan
     * sehingga bentuk request (URL, header, body) bisa diperiksa tanpa jaringan.
     */
    protected function httpRequest(): HttpRequest
    {
        return new HttpRequest($this->oauth2, $this->config);
    }
}

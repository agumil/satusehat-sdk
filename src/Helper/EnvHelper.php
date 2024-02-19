<?php
namespace agumil\SatuSehatSDK\Helper;

use Dotenv\Dotenv;

class EnvHelper
{
    const ENV = 'SATUSEHAT_ENV';
    const ORGANIZATION_ID = 'SATUSEHAT_ORGANIZATION_ID';
    const CLIENT_ID = 'SATUSEHAT_CLIENT_ID';
    const CLIENT_SECRET = 'SATUSEHAT_CLIENT_SECRET';

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->required([
            self::ENV,
            self::ORGANIZATION_ID,
            self::CLIENT_ID,
            self::CLIENT_SECRET,
        ]);
    }

    public static function isStaging(): bool
    {
        $env = @$_ENV[self::ENV];

        return $env === 'staging';
    }

    public static function isProduction(): bool
    {
        $env = @$_ENV[self::ENV];

        return $env === 'production';
    }

    public static function getOrganizationId()
    {
        return @$_ENV[self::ORGANIZATION_ID];
    }

    public static function getClientId()
    {
        return @$_ENV[self::CLIENT_ID];
    }

    public static function getClientSecret()
    {
        return @$_ENV[self::CLIENT_SECRET];
    }
}

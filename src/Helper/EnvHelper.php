<?php
namespace agumil\SatuSehatSDK\Helper;

use Dotenv\Dotenv;

class EnvHelper
{
    const ENV = 'SATUSEHAT_ENV';
    const CLIENT_ID = 'SATUSEHAT_CLIENT_ID';
    const CLIENT_SECRET = 'SATUSEHAT_CLIENT_SECRET';

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->required([
            'SATUSEHAT_ENV',
            'SATUSEHAT_CLIENT_ID',
            'SATUSEHAT_CLIENT_SECRET',
        ]);
    }

    public static function isDevelopment(): bool
    {
        $env = @$_ENV[self::ENV];

        return $env === 'development';
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

    public static function getClientId()
    {
        return @$_ENV[self::CLIENT_ID];
    }

    public static function getClientSecret()
    {
        return @$_ENV[self::CLIENT_SECRET];
    }
}

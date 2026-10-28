<?php
namespace agumil\SatuSehatSDK\Tests\Support;

/**
 * Mengosongkan `$_ENV` selama satu test.
 *
 * `EnvHelper` membaca `$_ENV` sebelum konfigurasi eksplisit, jadi test yang
 * mengandalkan `environment` dari parameter akan gagal di mesin yang punya
 * `SATUSEHAT_ENV` di environment-nya.
 */
trait EnvIsolation
{
    private array $env_backup = [];

    protected function isolateEnvironment(): void
    {
        $this->env_backup = $_ENV;

        unset(
            $_ENV['SATUSEHAT_ENV'],
            $_ENV['SATUSEHAT_ORGANIZATION_ID'],
            $_ENV['SATUSEHAT_CLIENT_ID'],
            $_ENV['SATUSEHAT_CLIENT_SECRET']
        );
    }

    protected function restoreEnvironment(): void
    {
        $_ENV = $this->env_backup;
    }
}

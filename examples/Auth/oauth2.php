<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Helper\EnvHelper;

try {
    $config['environment'] = EnvHelper::getEnvironment();
    $config['organization_id'] = $parentOrganizationId = EnvHelper::getOrganizationId();
    $config['client_id'] = EnvHelper::getClientId();
    $config['client_secret'] = EnvHelper::getClientSecret();

    $oauth2 = new Oauth2($config);
} catch (SSEnvException $sse) {
    die($sse->getMessage() . PHP_EOL);
}

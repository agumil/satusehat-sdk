<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;

$config['base_url'] = Endpoint::DEV_OAUTH2;
$config['client_id'] = 'client_id';
$config['client_secret'] = 'client_secret';

$oauth2 = new Oauth2($config);

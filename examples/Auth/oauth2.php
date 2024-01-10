<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;

$config['base_url'] = Endpoint::DEV_OAUTH2;
$config['client_id'] = 'clientid';
$config['client_secret'] = 'clientsecret';

$oauth2 = new Oauth2($config);

var_dump($oauth2->getToken());

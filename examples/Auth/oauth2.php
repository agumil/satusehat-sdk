<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;

$config['base_url'] = Endpoint::DEV_OAUTH2;
$config['client_id'] = 'WBhfPxhwhp69djSVR6AASt6XKwAhVAaVLOOSrrVdvDFCSjKP';
$config['client_secret'] = 'LOazqXsWwfKAAO1ppg8MqLTQCqK9uhein9dUZE2j8NOJrdDu553Yboc5WbQAPjze';

$oauth2 = new Oauth2($config);

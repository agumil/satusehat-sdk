<?php require __DIR__ . '/../../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\SSClient;

$config1['base_url'] = Endpoint::DEV_OAUTH2;
$config1['client_id'] = 'your_client_id';
$config1['client_secret'] = 'your_client_secret';

$config2['base_url'] = Endpoint::DEV_FHIR;

$ssclient = new SSClient(new Oauth2($config1), $config2);
$response = $ssclient->getPractitioner([
    'identifier' => 'https://fhir.kemkes.go.id/id/nakes-his-number|10009880728',
]);

var_dump($response->getContentAsObject());

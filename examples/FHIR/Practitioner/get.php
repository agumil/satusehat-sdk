<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'development']);

// search by nik
$params['identifier'] = 'https://fhir.kemkes.go.id/id/nik|7209061211900001';
$response = $ssclient->getPractitioner($params);

var_dump($response->getContentAsObject());

// search by id
$id = 10009880728;
$response = $ssclient->getPractitionerById($id);

var_dump($response->getContentAsObject());

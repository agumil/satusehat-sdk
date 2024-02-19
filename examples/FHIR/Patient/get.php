<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

// get by name
$params['identifier'] = 'https://fhir.kemkes.go.id/id/nik|9271060312000001';
$response = $ssclient->getPatient($params);
var_dump($response->getContentAsObject());

// get by id
$id = 'P02478375538';
$response = $ssclient->getPatientById($id);

var_dump($response->getContentAsObject());

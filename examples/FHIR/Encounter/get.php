<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

// get by subject
$params['subject'] = 'P02478375538';
$response = $ssclient->getEncounter($params);
var_dump($response->getContentAsObject());

// get by id
$id = 'fa715450-b1ae-4228-9916-546de687f700';
$response = $ssclient->getEncounterById($id);
var_dump($response->getContentAsObject());

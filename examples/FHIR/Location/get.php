<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

// get by organization
$params['organization'] = '10000004';
$response = $ssclient->getLocation($params);
var_dump($response->getContentAsObject());

// get by id
$id = 'dc01c797-547a-4e4d-97cd-4ece0630e380';
$response = $ssclient->getLocationById($id);
var_dump($response->getContentAsObject());

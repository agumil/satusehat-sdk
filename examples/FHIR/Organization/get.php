<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

// get by name
$params['name'] = 'Demo Organization';
$response = $ssclient->getOrganization($params);

var_dump($response->getContentAsObject());

// get by id
$id = '90a77a02-a142-466d-9d18-1cc08bb211d9';
$response = $ssclient->getOrganizationById($id);

var_dump($response->getContentAsObject());

<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['base_url' => Endpoint::DEV_FHIR]);

// get by name
$params['name'] = 'Demo Organization';
$response = $ssclient->getOrganization($params);

var_dump($response->getContentAsObject());

// get by id
$id = '67e1b275-4d22-43b4-b939-bc2d3e27b83d';
$response = $ssclient->getOrganizationById($id);

var_dump($response->getContentAsObject());

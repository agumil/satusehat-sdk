<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

$params['page'] = 1;
$params['size'] = 10;
$params['atc_code'] = 'L';
$params['level'] = 1;

$response = $kfaclient->getListProductByATC($params);

var_dump($response->getContentAsObject());

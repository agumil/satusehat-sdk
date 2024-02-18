<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'development', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

$params['identifier'] = 'kfa';
$params['code'] = '93000108';

$response = $kfaclient->getProduct($params);

var_dump($response->getContentAsObject());

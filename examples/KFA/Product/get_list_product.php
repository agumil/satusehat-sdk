<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

$params['page'] = 1;
$params['size'] = 10;
$params['product_type'] = 'farmasi';
// $params['from_date'] = '2023-01-01';
// $params['to_date'] = '2024-12-31';
// $params['keyword'] = 'paracetamol';
// $params['template_code'] = '92000888';
// $params['packaging_code'] = '94009294';

$response = $kfaclient->getListProduct($params);

var_dump($response->getContentAsObject());

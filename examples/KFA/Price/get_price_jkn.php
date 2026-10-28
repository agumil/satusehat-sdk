<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

$params['page'] = 1;
$params['limit'] = 10;
$params['kfa_code'] = '93004418';
// $params['region_code'] = 'regional1';
// $params['document_ref'] = 'HK.02.02/MENKES/123/2024';

$response = $kfaclient->getListPriceJKN($params);

var_dump($response->getContentAsObject());

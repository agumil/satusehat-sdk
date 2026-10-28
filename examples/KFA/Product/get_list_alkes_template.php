<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

// Body dikirim sebagai JSON apa adanya (KFA v3 - ALKES template).
$params['page'] = 1;
$params['size'] = 10;
$params['farmalkes_type'] = 'device';

$response = $kfaclient->getListAlkesTemplate($params);

var_dump($response->getContentAsObject());

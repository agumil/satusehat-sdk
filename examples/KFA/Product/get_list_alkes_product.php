<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

// Body dikirim sebagai JSON apa adanya (KFA v3 - ALKES).
$params['page'] = 1;
$params['size'] = 10;
$params['farmalkes_type'] = 'device';
// $params['state'] = 'valid';
// $params['active'] = true;
// $params['search'] = 'sarung tangan';
// $params['kfa_code'] = '91000000001';

$response = $kfaclient->getListAlkesProduct($params);

var_dump($response->getContentAsObject());

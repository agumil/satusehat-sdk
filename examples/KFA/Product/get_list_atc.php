<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKFA;

$kfaclient_config = [
    'environment' => 'staging', // required
    'timeout' => 30, // optional
];
$kfaclient = new SSClientKFA($oauth2, $kfaclient_config);

$params['page'] = 1;
$params['size'] = 10;
$params['level'] = 1;
$params['tag_code'] = 'kanker';
// $params['parent_code'] = 'kanker';
// $params['publish'] = 'True';

$response = $kfaclient->getListATC($params);

var_dump($response->getContentAsObject());

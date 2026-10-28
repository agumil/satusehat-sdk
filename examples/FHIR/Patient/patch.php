<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

// update parsial memakai JSON Patch (RFC 6902) — Patient hanya mendukung PATCH
$id = 'P02478375538';
$params = [
    [
        'op' => 'replace',
        'path' => '/language',
        'value' => 'id',
    ],
];

try {
    $response = $ssclient->updatePatient($id, $params);

    var_dump($response->getContentAsObject());
} catch (Exception $e) {
    echo $e->getMessage();
}

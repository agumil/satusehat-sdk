<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKYC;

$kycclient_config = [
    // SATUSEHAT_ENV di .env didahulukan; nilai ini dipakai bila .env kosong.
    'environment' => 'development', // development|staging|production
    'timeout' => 30, // optional
];
$kycclient = new SSClientKYC($oauth2, $kycclient_config);

// url_token berasal dari dekripsi respons generate-url() di sisi aplikasi.
$url_token = 'isi-dengan-url-token-hasil-dekripsi';

// Mode debug hanya untuk development/staging: challenge code langsung
// dikembalikan tanpa widget KYC. Server production menolak header ini.
$debug_mode = true;

$response = $kycclient->requestPerNik(
    '3171022809990001',
    'Budi Santoso',
    $url_token,
    $debug_mode
);

$body = $response->getContentAsObject();

if ($response->getHttpCode() === 200) {
    // ihs_number: IHS Number pasien yang cocok dengan NIK tersebut.
    // challenge_code: kode 6 digit, berlaku sampai expired_timestamp.
    var_dump($body->data->ihs_number);
    var_dump($body->data->challenge_code);
    var_dump($body->data->expired_timestamp);
} else {
    var_dump($response->getHttpCode());
    var_dump($response->getErrorMessages());
}

<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\SSClientKYC;

$kycclient_config = [
    // SATUSEHAT_ENV di .env didahulukan; nilai ini dipakai bila .env kosong.
    'environment' => 'development', // development|staging|production
    'timeout' => 30, // optional
];
$kycclient = new SSClientKYC($oauth2, $kycclient_config);

// Ciphertext ini dihasilkan proses enkripsi faskes memakai kunci publik dari
// Kemkes. SDK hanya mengirimkannya apa adanya — tidak mengenkripsi, tidak
// mendekripsi. Salin dari hasil enkripsi milik Anda.
$encrypted_message = <<<'MSG'
-----BEGIN ENCRYPTED MESSAGE-----
<base64 ciphertext dari proses enkripsi faskes>
-----END ENCRYPTED MESSAGE-----
MSG;

$response = $kycclient->generateUrl($encrypted_message);

// Respons sukses masih berupa pesan terenkripsi (text/plain). Dekripsinya di
// sisi aplikasi; di dalamnya ada `url_token` untuk langkah challenge-code.
var_dump($response->getHttpCode());
var_dump($response->getContent());

<?php
namespace agumil\SatuSehatSDK\Auth;

/**
 * Store yang menyimpan token di luar memori proses (mis. file) sehingga isinya
 * harus bisa dibedakan dari file yang ditulis pihak lain.
 *
 * `Oauth2` mengisi kunci ini dengan `client_secret` milik aplikasi. Dengan
 * begitu file token hanya dipercaya bila dibuat oleh aplikasi yang memegang
 * kredensial yang sama; file berisi `{"token":"..."}` buatan pihak lain
 * ditolak. Ini bukan perlindungan kerahasiaan (secret tidak disimpan di file),
 * melainkan perlindungan integritas: mencegah data klinis diarahkan ke
 * organisasi lain lewat token yang disisipkan.
 */
interface KeyedTokenStoreInterface extends TokenStoreInterface
{
    public function setSigningKey(string $key): void;
}

<?php
namespace agumil\SatuSehatSDK\Helper;

/**
 * Penyaring data pribadi pasien (PHI) sebelum masuk ke log.
 *
 * SDK ini mengirim payload FHIR yang berisi nama, NIK, tanggal lahir, alamat,
 * dan kontak. Bila objek itu dicatat apa adanya, log aplikasi berubah menjadi
 * salinan data pasien — persis yang dihindari UU No. 27/2022 (PDP).
 *
 * Pendekatan yang dipakai adalah **default-deny**, bukan daftar hitam kunci.
 * Versi sebelumnya menyamarkan kunci yang namanya mengandung `name`,
 * `identifier`, dan sejenisnya, lalu membiarkan sisanya apa adanya. Daftar
 * hitam selalu kalah cepat: FHIR menyimpan identitas di tempat yang namanya
 * tidak mengandung kata sensitif sama sekali —
 *
 *   subject.display : "Budi Santoso"                     (nama manusia)
 *   text.div        : "<div>Pasien Budi, NIK 3171..., DBD</div>"
 *   note.text       : "Pasien mengaku demam tiga hari"
 *
 * Ketiganya lolos utuh pada versi lama. Sekarang semua nilai disamarkan
 * kecuali beberapa kunci struktural yang tidak mungkin memuat identitas, dan
 * struktur (nama kunci) tetap dipertahankan agar log masih bisa dipakai untuk
 * menelusuri masalah.
 */
class PhiRedactor
{
    const MASK = '[REDACTED]';

    /**
     * Kunci yang nilainya boleh dicatat: metadata struktural FHIR. `status`
     * dan `resourceType` menjelaskan *jenis* data, bukan *isi* data.
     *
     * Daftar ini sengaja sangat pendek. Menambah kunci berarti menjamin kunci
     * itu tidak pernah memuat identitas pasien, dan untuk FHIR hampir semua
     * kunci lain bisa memuatnya (`code`, `type`, `display`, `text`, `value`,
     * `div`, `note`, `reasonCode`, ...).
     */
    const SAFE_KEYS = [
        'resourcetype',
        'status',
    ];

    /**
     * Parameter query yang boleh tampil: kendali paging dan format. Parameter
     * pencarian (`identifier`, `name`, `birthdate`, `nik`, ...) tidak termasuk.
     */
    const SAFE_QUERY_PARAMS = [
        '_count',
        '_offset',
        '_format',
        '_sort',
        '_summary',
        '_elements',
        '_since',
        'page',
    ];

    /**
     * Menyamarkan seluruh nilai pada struktur data, menyisakan nama kunci.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public static function redact($data)
    {
        if (is_object($data)) {
            // stdClass/objek lain bisa ikut terkirim sebagai payload; properti
            // privat tidak terlihat dari sini, jadi hanya yang publik dibaca.
            $data = get_object_vars($data);
        }

        if (!is_array($data)) {
            return self::MASK;
        }

        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && self::isSafeKey($key) && !is_array($value) && !is_object($value)) {
                $result[$key] = $value;

                continue;
            }

            $result[$key] = self::redact($value);
        }

        return $result;
    }

    /**
     * Menyamarkan nilai query string sekaligus `id` resource di path.
     *
     * `GET /fhir-r4/v1/Patient/100000030009` menaruh nomor IHS pasien di path,
     * bukan di query, sehingga menyaring query saja tidak cukup. Segmen yang
     * mengikuti nama resource FHIR (PascalCase) dianggap id dan disamarkan;
     * nama operasi seperti `$everything` dibiarkan karena diawali `$`.
     */
    public static function redactUrl(string $url): string
    {
        $query_start = strpos($url, '?');

        if ($query_start === false) {
            return self::redactPath($url);
        }

        $base = substr($url, 0, $query_start);
        parse_str(substr($url, $query_start + 1), $params);

        $pairs = [];

        foreach ($params as $key => $value) {
            $key = (string) $key;
            $safe = self::isSafeQueryParam($key) && is_scalar($value);
            $pairs[] = rawurlencode($key) . '=' . ($safe ? rawurlencode((string) $value) : self::MASK);
        }

        return self::redactPath($base) . ($pairs === [] ? '' : '?' . implode('&', $pairs));
    }

    private static function redactPath(string $url): string
    {
        $segments = explode('/', $url);
        $total = count($segments);

        for ($i = 1; $i < $total; $i++) {
            $previous = $segments[$i - 1];
            $current = $segments[$i];

            if ($current === '' || !preg_match('/^[A-Z][A-Za-z0-9]*$/', $previous)) {
                continue;
            }

            if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\-\.]{0,63}$/', $current)) {
                continue;
            }

            $segments[$i] = self::MASK;

            // Segmen setelah id bukan id lagi (bisa nama operasi), jadi
            // pemeriksaan dilanjutkan dari segmen berikutnya.
            $i++;
        }

        return implode('/', $segments);
    }

    private static function isSafeKey(string $key): bool
    {
        return in_array(strtolower($key), self::SAFE_KEYS, true);
    }

    private static function isSafeQueryParam(string $key): bool
    {
        return in_array($key, self::SAFE_QUERY_PARAMS, true);
    }
}

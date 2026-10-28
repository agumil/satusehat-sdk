<?php
namespace agumil\SatuSehatSDK\Helper;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class ValidatorHelper
{
    public static function dateTime($val)
    {
        preg_match('/([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1])(T([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?)?)?(Z|(\+|-)((0[0-9]|1[0-3]):[0-5][0-9]|14:00)?)?)?/', $val, $match);

        if (empty($match)) {
            throw new SSDataTypeException('DateTime format is not recognized. The valid format is YYYY, YYYY-MM, YYYY-MM-DD or YYYY-MM-DDThh:mm:ss+zz:zz, e.g. 2018, 1973-06, 1905-08-23, 2015-02-07T13:28:17-05:00 or 2017-01-01T00:00:00.000Z');
        }

        if ($val !== $match[0]) {
            return (new DateHelper($val))->dateTimeISO8601();
        }

        return $match[0];
    }

    public static function date(string $val)
    {
        preg_match('/([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1]))?)?/', $val, $match);

        if (empty($match)) {
            throw new SSDataTypeException('Date format is not recognized. The valid format is YYYY, YYYY-MM, or YYYY-MM-DD, e.g. 2018, 1973-06, or 1905-08-23');
        }

        if ($val !== $match[0]) {
            return (new DateHelper($val))->dateISO8601();
        }

        return $match[0];
    }

    public static function time(string $val)
    {
        preg_match('/([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?/', $val, $match);

        if (empty($match)) {
            throw new SSDataTypeException('Time format is not recognized. The valid format is hh:mm:ss, e.g. 12:30:00.');
        }

        if ($val !== $match[0]) {
            return (new DateHelper($val))->timeISO8601();
        }

        return $match[0];
    }

    public static function in(string $var_name, $needle, array $haystack)
    {
        if (!in_array($needle, $haystack)) {
            $stack_str = implode(',', $haystack);
            throw new SSDataTypeException("{$var_name} must be one of {$stack_str}");
        }
    }

    /**
     * NIK selalu 16 digit angka. Diperiksa sebagai string supaya NIK yang
     * diawali nol (mis. 0171...) tidak kehilangan digitnya saat dikonversi
     * menjadi integer.
     */
    public static function nik(string $var_name, $val)
    {
        if (!is_string($val) || !preg_match('/^[0-9]{16}$/', $val)) {
            throw new SSDataTypeException("{$var_name} is not a valid NIK. The valid value is exactly 16 digits, e.g. 3171022809990001.");
        }
    }

    public static function unsignedInt(string $var_name, string | int $val)
    {
        preg_match('/[0]|([1-9][0-9]*)/', $val, $match);

        if (empty($match) || $val != @$match[0]) {
            throw new SSDataTypeException("{$var_name} is not valid. The valid value is any non-negative integer, e.g. 0, 1, 2, 3, 2147483647, etc.");
        }
    }

    public static function positiveInt(string $var_name, string | int $val)
    {
        preg_match('/[1-9][0-9]*/', $val, $match);

        if (empty($match) || $val != @$match[0]) {
            throw new SSDataTypeException("{$var_name} is not valid. The valid value is any positive integer, e.g. 1, 2, 3, 2147483647, etc.");
        }
    }

    public static function decimal(string $var_name, $val)
    {
        preg_match('/-?(0|[1-9][0-9]{0,17})(\.[0-9]{1,17})?([eE][+-]?[0-9]{1,9}})?/', $val, $match);

        if (empty($match) || $val != @$match[0]) {
            throw new SSDataTypeException("{$var_name} is not valid. Rational numbers must be a decimal representation. Decimals in FHIR cannot have more than 18 digits and a decimal point.");
        }
    }

    public static function required(string $var_name, $val)
    {
        // Sengaja tidak memakai empty(): empty() menganggap 0 dan false sebagai
        // "kosong", padahal keduanya nilai yang sah untuk sebuah field FHIR.
        // required() hanya memeriksa kehadiran nilai, bukan tipe atau isinya.
        $is_missing = $val === null
            || $val === ''
            || (is_string($val) && trim($val) === '');

        if ($is_missing) {
            throw new SSDataTypeException("{$var_name} is required.");
        }
    }

    /**
     * Memvalidasi `id` resource FHIR sebelum dipakai menyusun URL.
     *
     * `id` diinterpolasi mentah ke path (`Patient/{$id}`), jadi nilai seperti
     * `../../Consent/abc?x=1` dapat menggeser target request ke resource lain
     * memakai Bearer token milik aplikasi. Pola di bawah mengikuti batasan id
     * FHIR (1-64 karakter, huruf/angka/titik/strip) dan sengaja mewajibkan
     * karakter pertama berupa huruf atau angka supaya `.` dan `..` ditolak.
     */
    public static function resourceId(string $var_name, string $id): string
    {
        if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\-\.]{0,63}$/', $id)) {
            throw new SSDataTypeException("{$var_name} is not a valid resource id. The valid value is 1-64 characters of letters, digits, hyphen, or dot, starting with a letter or digit.");
        }

        return $id;
    }
}

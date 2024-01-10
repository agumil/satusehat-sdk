<?php
namespace agumil\SatuSehatSDK\Helper;

class StrHelper
{
    public static function appendSlash(string $string): string
    {
        $last_char = substr($string, -1, 1);
        if ($last_char !== '/') {
            $string = $string . '/';
        }

        return $string;
    }

    public static function isJson(string $string): bool
    {
        if (PHP_VERSION >= 8.3) {
            return json_validate($string);
        } else {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        }
    }
}

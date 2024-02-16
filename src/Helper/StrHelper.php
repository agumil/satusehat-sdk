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

    public static function random(int $length = 8, string $type = 'alnum')
    {
        if ($length <= 0) {
            return '';
        }

        $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeric = '0123456789';
        $symbol = '~!@#$%&*=_+-';

        if ($length == 1) {
            $result = $alpha . $numeric . $symbol;

            return $result[rand(0, strlen($result))];
        }

        $result = '';
        switch ($type) {
            case 'alpha':
                while (strlen($result) < $length) {
                    $result .= $alpha[rand(0, strlen($alpha) - 1)];
                }
                break;

            case 'numeric':
                while (strlen($result) < $length) {
                    $result .= $numeric[rand(0, strlen($numeric) - 1)];
                }
                break;

            case 'alnumsym':
                if ($length < 3) {
                    $alpha_len = rand(0, 1);
                    $numeric_len = rand(0, 1);
                    $symbol_len = $length - $alpha_len - $numeric_len;
                } elseif ($length == 3) {
                    $alpha_len = 1;
                    $numeric_len = 1;
                    $symbol_len = 1;
                } else {
                    $alpha_len = rand(1, $length - 2);
                    $numeric_len = rand(1, ($length - $alpha_len) - 1);
                    $symbol_len = $length - $alpha_len - $numeric_len;
                }

                $result = '';
                for ($i = 0; $i < $alpha_len; $i++) {
                    $result .= $alpha[rand(0, strlen($alpha) - 1)];
                }

                for ($i = 0; $i < $numeric_len; $i++) {
                    $result .= $numeric[rand(0, strlen($numeric) - 1)];
                }

                for ($i = 0; $i < $symbol_len; $i++) {
                    $result .= $symbol[rand(0, strlen($symbol) - 1)];
                }
                break;

            default:
                $alpha_len = rand(1, $length - 1);
                $numeric_len = $length - $alpha_len;

                $result = '';
                for ($i = 0; $i < $alpha_len; $i++) {
                    $result .= $alpha[rand(0, strlen($alpha) - 1)];
                }

                for ($i = 0; $i < $numeric_len; $i++) {
                    $result .= $numeric[rand(0, strlen($numeric) - 1)];
                }
                break;
        }

        return str_shuffle($result);
    }
}

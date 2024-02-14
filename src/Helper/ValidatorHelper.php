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

        return $match[0];
    }

    public static function date(string $val)
    {
        preg_match('/([0-9]([0-9]([0-9][1-9]|[1-9]0)|[1-9]00)|[1-9]000)(-(0[1-9]|1[0-2])(-(0[1-9]|[1-2][0-9]|3[0-1]))?)?/', $val, $match);

        if (empty($match)) {
            throw new SSDataTypeException('Date format is not recognized. The valid format is YYYY, YYYY-MM, or YYYY-MM-DD, e.g. 2018, 1973-06, or 1905-08-23');
        }

        return $match[0];
    }

    public static function time(string $val)
    {
        preg_match('/([01][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)(\.[0-9]{1,9})?/', $val, $match);

        if (empty($match)) {
            throw new SSDataTypeException('Time format is not recognized. The valid format is hh:mm:ss, e.g. 12:30:00.');
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
}

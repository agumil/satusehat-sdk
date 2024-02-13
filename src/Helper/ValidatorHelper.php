<?php
namespace agumil\SatuSehatSDK\Helper;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class ValidatorHelper
{
    public static function dateTime(string $var_name, $val)
    {
        $epoch = strtotime($val);
        if ($epoch === false) {
            throw new SSDataTypeException("{$var_name} is unparseable by strtotime. Please provide a valid date, dateTime, or time.");
        }
    }

    public static function in(string $var_name, $needle, array $haystack)
    {
        if (!in_array($needle, $haystack)) {
            $stack_str = implode(',', $haystack);
            throw new SSDataTypeException("{$var_name} must be one of {$stack_str}");
        }
    }
}

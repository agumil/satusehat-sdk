<?php
namespace agumil\SatuSehatSDK\Helper;

class ValidatorHelper
{
    public static function validDateTime($var)
    {
        $epochStart = strtotime($var);
        return $epochStart !== false;
    }
}

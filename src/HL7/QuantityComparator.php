<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class QuantityComparator implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/quantity-comparator';

    const CODE_LT = '<';
    const CODE_LTE = '<=';
    const CODE_GT = '>';
    const CODE_GTE = '>=';
    const CODE_AD = 'ad';

    public static function getCodes(): array
    {
        return [
            self::CODE_LT,
            self::CODE_LTE,
            self::CODE_GT,
            self::CODE_GTE,
            self::CODE_AD,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_LT => 'Less than',
            self::CODE_LTE => 'Less or Equal to',
            self::CODE_GT => 'Greater or Equal to',
            self::CODE_GTE => 'Greater than',
            self::CODE_AD => 'Sufficient to achieve this total quantity',
        ];

        return @$displays[$code];
    }
}

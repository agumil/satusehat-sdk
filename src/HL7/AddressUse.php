<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class AddressUse implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/address-use';

    const CODE_HOME = 'home';
    const CODE_WORK = 'work';
    const CODE_TEMP = 'temp';
    const CODE_OLD = 'old';
    const CODE_BILLING = 'billing';

    public static function getCodes(): array
    {
        return [
            self::CODE_HOME,
            self::CODE_WORK,
            self::CODE_TEMP,
            self::CODE_OLD,
            self::CODE_BILLING,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_HOME => 'Home',
            self::CODE_WORK => 'Work',
            self::CODE_TEMP => 'Temporary',
            self::CODE_OLD => 'Old / Incorrect',
            self::CODE_BILLING => 'Billing',
        ];

        return @$displays[$code];
    }
}

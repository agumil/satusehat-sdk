<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class ContactPointUse implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/ValueSet/contact-point-use';

    const CODE_HOME = 'home';
    const CODE_WORK = 'work';
    const CODE_TEMP = 'temp';
    const CODE_OLD = 'old';
    const CODE_MOBILE = 'mobile';

    public static function getCodes(): array
    {
        return [
            self::CODE_HOME,
            self::CODE_WORK,
            self::CODE_TEMP,
            self::CODE_OLD,
            self::CODE_MOBILE,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_HOME => 'Home',
            self::CODE_WORK => 'Work',
            self::CODE_TEMP => 'Temporary',
            self::CODE_OLD => 'Old',
            self::CODE_MOBILE => 'Mobile',
        ];

        return @$displays[$code];
    }
}

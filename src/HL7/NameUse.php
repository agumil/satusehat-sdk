<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class NameUse implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/name-use';

    const CODE_USUAL = 'usual';
    const CODE_OFFICIAL = 'official';
    const CODE_TEMP = 'temp';
    const CODE_NICKNAME = 'nickname';
    const CODE_ANONYMOUS = 'anonymous';
    const CODE_OLD = 'old';
    const CODE_MAIDEN = 'maiden';

    public static function getCodes(): array
    {
        return [
            self::CODE_USUAL,
            self::CODE_OFFICIAL,
            self::CODE_TEMP,
            self::CODE_NICKNAME,
            self::CODE_ANONYMOUS,
            self::CODE_OLD,
            self::CODE_MAIDEN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_USUAL => 'Usual',
            self::CODE_OFFICIAL => 'Official',
            self::CODE_TEMP => 'Temp',
            self::CODE_NICKNAME => 'Nickname',
            self::CODE_ANONYMOUS => 'Anonymous',
            self::CODE_OLD => 'Old',
            self::CODE_MAIDEN => 'Maiden',
        ];

        return @$displays[$code];
    }
}

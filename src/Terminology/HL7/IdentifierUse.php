<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class IdentifierUse implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/identifier-use';

    const CODE_USUAL = 'usual';
    const CODE_OFFICIAL = 'official';
    const CODE_TEMP = 'temp';
    const CODE_SECONDARY = 'secondary';
    const CODE_OLD = 'old';

    public static function getCodes(): array
    {
        return [
            self::CODE_USUAL,
            self::CODE_OFFICIAL,
            self::CODE_TEMP,
            self::CODE_SECONDARY,
            self::CODE_OLD,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_USUAL => 'Usual',
            self::CODE_OFFICIAL => 'Official',
            self::CODE_TEMP => 'Temp',
            self::CODE_SECONDARY => 'Secondary',
            self::CODE_OLD => 'Old',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class AdministrativeGender implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/administrative-gender';

    const CODE_MALE = 'male';
    const CODE_FEMALE = 'female';
    const CODE_OTHER = 'other';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_MALE,
            self::CODE_FEMALE,
            self::CODE_OTHER,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_MALE => 'Male',
            self::CODE_FEMALE => 'Female',
            self::CODE_OTHER => 'Other',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

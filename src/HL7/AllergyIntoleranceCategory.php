<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class AllergyIntoleranceCategory implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://hl7.org/fhir/allergy-intolerance-category';

    const CODE_FOOD = 'food';
    const CODE_MEDICATION = 'medication';
    const CODE_ENVIRONMENT = 'environment';
    const CODE_BIOLOGIC = 'biologic';

    public static function getCodes(): array
    {
        return [
            self::CODE_FOOD,
            self::CODE_MEDICATION,
            self::CODE_ENVIRONMENT,
            self::CODE_BIOLOGIC,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_FOOD => 'Food',
            self::CODE_MEDICATION => 'Medication',
            self::CODE_ENVIRONMENT => 'Environment',
            self::CODE_BIOLOGIC => 'Biologic',
        ];

        return @$displays[$code];
    }
}

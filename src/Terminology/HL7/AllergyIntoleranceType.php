<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class AllergyIntoleranceType implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/allergy-intolerance-type';

    const CODE_ALLERGY = 'allergy';
    const CODE_INTOLERANCE = 'intolerance';

    public static function getCodes(): array
    {
        return [
            self::CODE_ALLERGY,
            self::CODE_INTOLERANCE,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ALLERGY => 'Allergy',
            self::CODE_INTOLERANCE => 'Intolerance',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class MedicationRequestCategory implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/R4/codesystem-medicationrequest-category';

    const CODE_INPATIENT = 'inpatient';
    const CODE_OUTPATIENT = 'outpatient';
    const CODE_COMMUNITY = 'community';
    const CODE_DISCHARGE = 'discharge';

    public static function getCodes(): array
    {
        return [
            self::CODE_INPATIENT,
            self::CODE_OUTPATIENT,
            self::CODE_COMMUNITY,
            self::CODE_DISCHARGE,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_INPATIENT => 'Inpatient',
            self::CODE_OUTPATIENT => 'Outpatient',
            self::CODE_COMMUNITY => 'Community',
            self::CODE_DISCHARGE => 'Discharge',
        ];

        return @$displays[$code];
    }
}

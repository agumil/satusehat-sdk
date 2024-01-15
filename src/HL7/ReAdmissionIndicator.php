<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class ReAdmissionIndicator implements HL7Interface
{
    const VERSION = '2.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0092';

    const CODE_READMISSION = 'R';

    public static function getCodes(): array
    {
        return [
            self::CODE_READMISSION,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_READMISSION => 'Re-admission',
        ];

        return @$displays[$code];
    }
}

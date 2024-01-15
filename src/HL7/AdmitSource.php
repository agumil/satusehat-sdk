<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class AdmitSource implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/admit-source';

    const CODE_TRANSFERRED = 'hosp-trans';
    const CODE_EMERGENCY_DEPT = 'emd';
    const CODE_OUTPATIENT_DEPT = 'outp';
    const CODE_BORN = 'born';
    const CODE_GENERAL_PRACTITIONER = 'gp';
    const CODE_MEDICAL_PRACTITIONER = 'mp';
    const CODE_NURSING = 'nursing';
    const CODE_PSYCHIATRIC = 'psych';
    const CODE_REHABILITATION = 'rehab';
    const CODE_OTHER = 'other';

    public static function getCodes(): array
    {
        return [
            self::CODE_TRANSFERRED,
            self::CODE_EMERGENCY_DEPT,
            self::CODE_OUTPATIENT_DEPT,
            self::CODE_BORN,
            self::CODE_GENERAL_PRACTITIONER,
            self::CODE_MEDICAL_PRACTITIONER,
            self::CODE_NURSING,
            self::CODE_PSYCHIATRIC,
            self::CODE_REHABILITATION,
            self::CODE_OTHER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_TRANSFERRED => 'Transferred from other hospital',
            self::CODE_EMERGENCY_DEPT => 'From accident/emergency department',
            self::CODE_OUTPATIENT_DEPT => 'From outpatient department',
            self::CODE_BORN => 'Born in hospital',
            self::CODE_GENERAL_PRACTITIONER => 'General Practitioner referral',
            self::CODE_MEDICAL_PRACTITIONER => 'Medical Practitioner/physician referral',
            self::CODE_NURSING => 'From nursing home',
            self::CODE_PSYCHIATRIC => 'From psychiatric hospital',
            self::CODE_REHABILITATION => 'From rehabilitation facility',
            self::CODE_OTHER => 'Other',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class EncounterType implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/encounter-type';

    const CODE_ANNUAL_DIABETES_SCREENING = 'ADMS';
    const CODE_BONE_DRILLING_OR_MARROW_PUNCTION = 'BD/BM-clin';
    const CODE_INFANT_COLON_SCREENING = 'CCS60';
    const CODE_KENACORT_INJECTION = 'OKI';

    public static function getCodes(): array
    {
        return [
            self::CODE_ANNUAL_DIABETES_SCREENING,
            self::CODE_BONE_DRILLING_OR_MARROW_PUNCTION,
            self::CODE_INFANT_COLON_SCREENING,
            self::CODE_KENACORT_INJECTION,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ANNUAL_DIABETES_SCREENING => 'Annual diabetes mellitus screening',
            self::CODE_BONE_DRILLING_OR_MARROW_PUNCTION => 'Bone drilling/bone marrow punction in clinic',
            self::CODE_INFANT_COLON_SCREENING => 'Infant colon screening - 60 minutes',
            self::CODE_KENACORT_INJECTION => 'Outpatient Kenacort injection',
        ];

        return @$displays[$code];
    }
}

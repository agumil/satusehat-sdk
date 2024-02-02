<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class ConditionCategory implements HL7Interface
{
    const VERSION = '4.0.1';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/condition-category';

    const CODE_PROBLEM_LIST_ITEM = 'problem-list-item';
    const CODE_ENCOUNTER_DIAGNOSIS = 'encounter-diagnosis';

    public static function getCodes(): array
    {
        return [
            self::CODE_PROBLEM_LIST_ITEM,
            self::CODE_ENCOUNTER_DIAGNOSIS,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PROBLEM_LIST_ITEM => 'Problem List Diagnosis',
            self::CODE_ENCOUNTER_DIAGNOSIS => 'Encounter Diagnosis',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EncounterCode implements TerminologyInterface
{
    const VERSION = '9.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';

    const CODE_AMBULATORY = 'AMB';
    const CODE_EMERGENCY = 'EMER';
    const CODE_FIELD = 'FLD';
    const CODE_HOME_HEALTH = 'HH';
    const CODE_INPATIENT_ENCOUNTER = 'IMP';
    const CODE_INPATIENT_ACUTE = 'ACUTE';
    const CODE_INPATIENT_NONACUTE = 'NONAC';
    const CODE_OBSERVATION_ENCOUNTER = 'OBSENC';
    const CODE_PRE_ADMISSION = 'PRENC';
    const CODE_SHORT_STAY = 'SS';
    const CODE_VIRTUAL = 'VR';

    public static function getCodes(): array
    {
        return [
            self::CODE_AMBULATORY,
            self::CODE_EMERGENCY,
            self::CODE_FIELD,
            self::CODE_HOME_HEALTH,
            self::CODE_INPATIENT_ENCOUNTER,
            self::CODE_INPATIENT_ACUTE,
            self::CODE_INPATIENT_NONACUTE,
            self::CODE_OBSERVATION_ENCOUNTER,
            self::CODE_PRE_ADMISSION,
            self::CODE_SHORT_STAY,
            self::CODE_VIRTUAL,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_AMBULATORY => 'ambulatory',
            self::CODE_EMERGENCY => 'emergency',
            self::CODE_FIELD => 'field',
            self::CODE_HOME_HEALTH => 'home health',
            self::CODE_INPATIENT_ENCOUNTER => 'inpatient encounter',
            self::CODE_INPATIENT_ACUTE => 'inpatient acute',
            self::CODE_INPATIENT_NONACUTE => 'inpatient non-acute',
            self::CODE_OBSERVATION_ENCOUNTER => 'observation encounter',
            self::CODE_PRE_ADMISSION => 'pre-admission',
            self::CODE_SHORT_STAY => 'short stay',
            self::CODE_VIRTUAL => 'virtual',
        ];

        return @$displays[$code];
    }
}

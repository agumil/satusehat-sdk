<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EncounterStatus implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/encounter-status';

    const CODE_PLANNED = 'planned';
    const CODE_IN_PROGRESS = 'in-progress';
    const CODE_ON_HOLD = 'on-hold';
    const CODE_DISCHARGED = 'discharged';
    const CODE_COMPLETED = 'completed';
    const CODE_CANCELLED = 'cancelled';
    const CODE_DISCONTINUED = 'discontinued';
    const CODE_ENTER_IN_ERROR = 'entered-in-error';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_PLANNED,
            self::CODE_IN_PROGRESS,
            self::CODE_ON_HOLD,
            self::CODE_DISCHARGED,
            self::CODE_COMPLETED,
            self::CODE_CANCELLED,
            self::CODE_DISCONTINUED,
            self::CODE_ENTER_IN_ERROR,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PLANNED => 'Planned',
            self::CODE_IN_PROGRESS => 'In Progress',
            self::CODE_ON_HOLD => 'On Hold',
            self::CODE_DISCHARGED => 'Discharged',
            self::CODE_COMPLETED => 'Completed',
            self::CODE_CANCELLED => 'Cancelled',
            self::CODE_DISCONTINUED => 'Discontinued',
            self::CODE_ENTER_IN_ERROR => 'Enter in Error',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EventStatus implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/event-status';

    const CODE_PREPARATION = 'preparation';
    const CODE_IN_PROGRESS = 'in-progress';
    const CODE_NOT_DONE = 'not-done';
    const CODE_ON_HOLD = 'on-hold';
    const CODE_STOPPED = 'stopped';
    const CODE_COMPLETED = 'completed';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_PREPARATION,
            self::CODE_IN_PROGRESS,
            self::CODE_NOT_DONE,
            self::CODE_ON_HOLD,
            self::CODE_STOPPED,
            self::CODE_COMPLETED,
            self::CODE_ENTERED_IN_ERROR,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PREPARATION => 'Preparation',
            self::CODE_IN_PROGRESS => 'In Progress',
            self::CODE_NOT_DONE => 'Not Done',
            self::CODE_ON_HOLD => 'On Hold',
            self::CODE_STOPPED => 'Stopped',
            self::CODE_COMPLETED => 'Completed',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

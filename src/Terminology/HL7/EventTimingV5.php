<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EventTimingV5 implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/event-timing';

    const CODE_MORNING = 'MORN';
    const CODE_MORNING_EARLY = 'MORN.early';
    const CODE_MORNING_LATE = 'MORN.late';
    const CODE_NOON = 'NOON';
    const CODE_AFTERNOON = 'AFT';
    const CODE_AFTERNOON_EARLY = 'AFT.early';
    const CODE_AFTERNOON_LATE = 'AFT.late';
    const CODE_EVENING = 'EVE';
    const CODE_EVENING_EARLY = 'EVE.early';
    const CODE_EVENING_LATE = 'EVE.late';
    const CODE_NIGHT = 'NIGHT';
    const CODE_AFTER_SLEEP = 'PHS';

    public static function getCodes(): array
    {
        return [
            self::CODE_MORNING,
            self::CODE_MORNING_EARLY,
            self::CODE_MORNING_LATE,
            self::CODE_NOON,
            self::CODE_AFTERNOON,
            self::CODE_AFTERNOON_EARLY,
            self::CODE_AFTERNOON_LATE,
            self::CODE_EVENING,
            self::CODE_EVENING_EARLY,
            self::CODE_EVENING_LATE,
            self::CODE_NIGHT,
            self::CODE_AFTER_SLEEP,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_MORNING => 'Morning',
            self::CODE_MORNING_EARLY => 'Early Morning',
            self::CODE_MORNING_LATE => 'Late Morning',
            self::CODE_NOON => 'Noon',
            self::CODE_AFTERNOON => 'Afternoon',
            self::CODE_AFTERNOON_EARLY => 'Early Afternoon',
            self::CODE_AFTERNOON_LATE => 'Late Afternoon',
            self::CODE_EVENING => 'Evening',
            self::CODE_EVENING_EARLY => 'Early Evening',
            self::CODE_EVENING_LATE => 'Late Evening',
            self::CODE_NIGHT => 'Night',
            self::CODE_AFTER_SLEEP => 'After Sleep',
        ];

        return @$displays[$code];
    }
}

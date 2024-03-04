<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class DaysOfWeek implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/days-of-week';

    const CODE_MONDAY = 'mon';
    const CODE_TUESDAY = 'tue';
    const CODE_WEDNESDAY = 'wed';
    const CODE_THURSDAY = 'thu';
    const CODE_FRIDAY = 'fri';
    const CODE_SATURDAY = 'sat';
    const CODE_SUNDAY = 'sun';

    public static function getCodes(): array
    {
        return [
            self::CODE_MONDAY,
            self::CODE_TUESDAY,
            self::CODE_WEDNESDAY,
            self::CODE_THURSDAY,
            self::CODE_FRIDAY,
            self::CODE_SATURDAY,
            self::CODE_SUNDAY,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_MONDAY => 'Monday',
            self::CODE_TUESDAY => 'Tuesday',
            self::CODE_WEDNESDAY => 'Wednesday',
            self::CODE_THURSDAY => 'Thursday',
            self::CODE_FRIDAY => 'Friday',
            self::CODE_SATURDAY => 'Saturday',
            self::CODE_SUNDAY => 'Sunday',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class UnitsOfTime implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://unitsofmeasure.org';

    const CODE_SECOND = 's';
    const CODE_MINUTE = 'min';
    const CODE_HOUR = 'h';
    const CODE_DAY = 'd';
    const CODE_WEEK = 'wk';
    const CODE_MONTH = 'mo';
    const CODE_YEAR = 'a';

    public static function getCodes(): array
    {
        return [
            self::CODE_SECOND,
            self::CODE_MINUTE,
            self::CODE_HOUR,
            self::CODE_DAY,
            self::CODE_WEEK,
            self::CODE_MONTH,
            self::CODE_YEAR,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_SECOND => 'second',
            self::CODE_MINUTE => 'minute',
            self::CODE_HOUR => 'hour',
            self::CODE_DAY => 'day',
            self::CODE_WEEK => 'week',
            self::CODE_MONTH => 'month',
            self::CODE_YEAR => 'year',
        ];

        return @$displays[$code];
    }
}

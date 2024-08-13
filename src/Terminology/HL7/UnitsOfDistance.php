<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class UnitsOfDistance implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://unitsofmeasure.org';

    const CODE_NANOMETER = 'nm';
    const CODE_MICROMETER = 'um';
    const CODE_MILLIMETER = 'mm';
    const CODE_METER = 'm';
    const CODE_KILOMETER = 'km';

    public static function getCodes(): array
    {
        return [
            self::CODE_NANOMETER,
            self::CODE_MICROMETER,
            self::CODE_MILLIMETER,
            self::CODE_METER,
            self::CODE_KILOMETER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_NANOMETER => 'nanometers',
            self::CODE_MICROMETER => 'micrometers',
            self::CODE_MILLIMETER => 'millimeters',
            self::CODE_METER => 'meters',
            self::CODE_KILOMETER => 'kilometers',
        ];

        return @$displays[$code];
    }
}

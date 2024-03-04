<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class DoseAndRateType implements TerminologyInterface
{
    const VERSION = '0.1.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/dose-rate-type';

    const CODE_CALCULATED = 'calculated';
    const CODE_ORDERED = 'ordered';

    public static function getCodes(): array
    {
        return [
            self::CODE_CALCULATED,
            self::CODE_ORDERED,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_CALCULATED => 'Calculated',
            self::CODE_ORDERED => 'Ordered',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class BedStatus implements HL7Interface
{
    const VERSION = '2.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0116';

    const CODE_CLOSED = 'C';
    const CODE_HOUSEKEEPING = 'H';
    const CODE_OCCUPIED = 'O';
    const CODE_UNOCCUPIED = 'U';
    const CODE_CONTAMINATED = 'K';
    const CODE_ISOLATED = 'I';

    public static function getCodes(): array
    {
        return [
            self::CODE_CLOSED,
            self::CODE_HOUSEKEEPING,
            self::CODE_OCCUPIED,
            self::CODE_UNOCCUPIED,
            self::CODE_CONTAMINATED,
            self::CODE_ISOLATED,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_CLOSED => 'Closed',
            self::CODE_HOUSEKEEPING => 'Housekeeping',
            self::CODE_OCCUPIED => 'Occupied',
            self::CODE_UNOCCUPIED => 'Unoccupied',
            self::CODE_CONTAMINATED => 'Contaminated',
            self::CODE_ISOLATED => 'Isolated',
        ];

        return @$displays[$code];
    }
}

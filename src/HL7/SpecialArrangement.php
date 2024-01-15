<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class SpecialArrangement implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/encounter-special-arrangements';

    const CODE_WHEELCHAIR = 'wheel';
    const CODE_ADD_BEDDING = 'add-bed';
    const CODE_INTERPRETER = 'int';
    const CODE_ATTENDANT = 'att';
    const CODE_DOG = 'dog';

    public static function getCodes(): array
    {
        return [
            self::CODE_WHEELCHAIR,
            self::CODE_ADD_BEDDING,
            self::CODE_INTERPRETER,
            self::CODE_ATTENDANT,
            self::CODE_DOG,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_WHEELCHAIR => 'Wheelchair',
            self::CODE_ADD_BEDDING => 'Additional bedding',
            self::CODE_INTERPRETER => 'Interpreter',
            self::CODE_ATTENDANT => 'Attendant',
            self::CODE_DOG => 'Guide dog',
        ];

        return @$displays[$code];
    }
}

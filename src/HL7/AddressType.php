<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class AddressType implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/ValueSet/address-type';

    const CODE_POSTAL = 'postal';
    const CODE_PHYSICAL = 'physical';
    const CODE_BOTH = 'both';

    public static function getCodes(): array
    {
        return [
            self::CODE_POSTAL,
            self::CODE_PHYSICAL,
            self::CODE_BOTH,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_POSTAL => 'Postal',
            self::CODE_PHYSICAL => 'Physical',
            self::CODE_BOTH => 'Postal & Physical',
        ];

        return @$displays[$code];
    }
}

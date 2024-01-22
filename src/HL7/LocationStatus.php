<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class LocationStatus implements HL7Interface
{
    const VERSION = '4.0.1';
    const SYSTEM = 'http://hl7.org/fhir/location-status';

    const CODE_ACTIVE = 'active';
    const CODE_SUSPENDED = 'suspended';
    const CODE_INACTIVE = 'inactive';

    public static function getCodes(): array
    {
        return [
            self::CODE_ACTIVE,
            self::CODE_SUSPENDED,
            self::CODE_INACTIVE,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ACTIVE => 'Active',
            self::CODE_SUSPENDED => 'Suspended',
            self::CODE_INACTIVE => 'Inactive',
        ];

        return @$displays[$code];
    }
}

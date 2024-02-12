<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class AllergyIntoleranceClinicalStatus implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical';

    const CODE_ACTIVE = 'active';
    const CODE_INACTIVE = 'inactive';
    const CODE_RESOLVED = 'resolved';

    public static function getCodes(): array
    {
        return [
            self::CODE_ACTIVE,
            self::CODE_INACTIVE,
            self::CODE_RESOLVED,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ACTIVE => 'Active',
            self::CODE_INACTIVE => 'Inactive',
            self::CODE_RESOLVED => 'Resolved',
        ];

        return @$displays[$code];
    }
}

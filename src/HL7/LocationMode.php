<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class LocationMode implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/location-mode';

    const CODE_INSTANCE = 'instance';
    const CODE_KIND = 'kind';

    public static function getCodes(): array
    {
        return [
            self::CODE_INSTANCE,
            self::CODE_KIND,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_INSTANCE => 'Instance',
            self::CODE_KIND => 'Kind',
        ];

        return @$displays[$code];
    }
}

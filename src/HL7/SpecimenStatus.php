<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class SpecimenStatus implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/specimen-status';

    const CODE_AVAILABLE = 'available';
    const CODE_UNAVAILABLE = 'unavailable';
    const CODE_UNSATISFACTORY = 'unsatisfactory';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';

    public static function getCodes(): array
    {
        return [
            self::CODE_AVAILABLE,
            self::CODE_UNAVAILABLE,
            self::CODE_UNSATISFACTORY,
            self::CODE_ENTERED_IN_ERROR,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_AVAILABLE => 'Available',
            self::CODE_UNAVAILABLE => 'Unavailable',
            self::CODE_UNSATISFACTORY => 'Unsatisfactory',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
        ];

        return @$displays[$code];
    }
}

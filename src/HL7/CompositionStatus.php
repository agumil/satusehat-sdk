<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class CompositionStatus implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/composition-status';

    const CODE_PRELIMINARY = 'preliminary';
    const CODE_FINAL = 'final';
    const CODE_AMENDED = 'amended';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';

    public static function getCodes(): array
    {
        return [
            self::CODE_PRELIMINARY,
            self::CODE_FINAL,
            self::CODE_AMENDED,
            self::CODE_ENTERED_IN_ERROR,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PRELIMINARY => 'Preliminary',
            self::CODE_FINAL => 'Final',
            self::CODE_AMENDED => 'Amended',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
        ];

        return @$displays[$code];
    }
}

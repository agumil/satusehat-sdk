<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class AllergyIntoleranceVerificationStatus implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification';

    const CODE_UNCONFIRMED = 'unconfirmed';
    const CODE_CONFIRMED = 'confirmed';
    const CODE_REFUTED = 'refuted';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';

    public static function getCodes(): array
    {
        return [
            self::CODE_UNCONFIRMED,
            self::CODE_CONFIRMED,
            self::CODE_REFUTED,
            self::CODE_ENTERED_IN_ERROR,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_UNCONFIRMED => 'Unconfirmed',
            self::CODE_CONFIRMED => 'Confirmed',
            self::CODE_REFUTED => 'Refuted',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
        ];

        return @$displays[$code];
    }
}

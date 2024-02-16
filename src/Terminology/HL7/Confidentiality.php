<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class Confidentiality implements TerminologyInterface
{
    const VERSION = '3.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-Confidentiality';

    const CODE_UNRESTRICTED = 'U';
    const CODE_LOW = 'L';
    const CODE_MODERATE = 'M';
    const CODE_NORMAL = 'N';
    const CODE_RESTRICTED = 'R';
    const CODE_VERY_RESTRICTED = 'V';

    public static function getCodes(): array
    {
        return [
            self::CODE_UNRESTRICTED,
            self::CODE_LOW,
            self::CODE_MODERATE,
            self::CODE_NORMAL,
            self::CODE_RESTRICTED,
            self::CODE_VERY_RESTRICTED,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_UNRESTRICTED => 'unrestricted',
            self::CODE_LOW => 'low',
            self::CODE_MODERATE => 'moderate',
            self::CODE_NORMAL => 'normal',
            self::CODE_RESTRICTED => 'restricted',
            self::CODE_VERY_RESTRICTED => 'very restricted',
        ];

        return @$displays[$code];
    }
}

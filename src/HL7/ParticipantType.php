<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ParticipantType implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/participant-type';

    const CODE_TRANSLATOR = 'translator';
    const CODE_EMERGENCY = 'emergency';

    public static function getCodes(): array
    {
        return [
            self::CODE_TRANSLATOR,
            self::CODE_EMERGENCY,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_TRANSLATOR => 'Translator',
            self::CODE_EMERGENCY => 'Emergency',
        ];

        return @$displays[$code];
    }
}

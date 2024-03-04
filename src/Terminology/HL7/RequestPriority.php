<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class RequestPriority implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/request-priority';

    const CODE_ROUTINE = 'routine';
    const CODE_URGENT = 'urgent';
    const CODE_ASAP = 'ASAP';
    const CODE_STAT = 'STAT';

    public static function getCodes(): array
    {
        return [
            self::CODE_ROUTINE,
            self::CODE_URGENT,
            self::CODE_ASAP,
            self::CODE_STAT,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ROUTINE => 'Routine',
            self::CODE_URGENT => 'Urgent',
            self::CODE_ASAP => 'ASAP',
            self::CODE_STAT => 'Stat',
        ];

        return @$displays[$code];
    }
}

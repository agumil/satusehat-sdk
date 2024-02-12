<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ActPriority implements TerminologyInterface
{
    const VERSION = '3.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ActPriority';

    const CODE_ASAP = 'A';
    const CODE_CALLBACK_RESULT = 'CR';
    const CODE_CALLBACK_SCHEDULING = 'CS';
    const CODE_CALLBACK_PLACER = 'CSP';
    const CODE_CONTACT_RECIPIENT = 'CSR';
    const CODE_ELECTIVE = 'EL';
    const CODE_EMERGENCY = 'EM';
    const CODE_PREOP = 'P';
    const CODE_AS_NEEDED = 'PRN';
    const CODE_ROUTINE = 'R';
    const CODE_RUSH_REPORTING = 'RR';
    const CODE_STAT = 'S';
    const CODE_TIMING_CRITICAL = 'T';
    const CODE_USE_AS_DIRECTED = 'UD';
    const CODE_URGENT = 'UR';

    public static function getCodes(): array
    {
        return [
            self::CODE_ASAP,
            self::CODE_CALLBACK_RESULT,
            self::CODE_CALLBACK_SCHEDULING,
            self::CODE_CALLBACK_PLACER,
            self::CODE_CONTACT_RECIPIENT,
            self::CODE_ELECTIVE,
            self::CODE_EMERGENCY,
            self::CODE_PREOP,
            self::CODE_AS_NEEDED,
            self::CODE_ROUTINE,
            self::CODE_RUSH_REPORTING,
            self::CODE_STAT,
            self::CODE_TIMING_CRITICAL,
            self::CODE_USE_AS_DIRECTED,
            self::CODE_URGENT,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ASAP => 'ASAP',
            self::CODE_CALLBACK_RESULT => 'callback results',
            self::CODE_CALLBACK_SCHEDULING => 'callback for scheduling',
            self::CODE_CALLBACK_PLACER => 'callback placer for scheduling',
            self::CODE_CONTACT_RECIPIENT => 'contact recipient for scheduling',
            self::CODE_ELECTIVE => 'elective',
            self::CODE_EMERGENCY => 'emergency',
            self::CODE_PREOP => 'preop',
            self::CODE_AS_NEEDED => 'as needed',
            self::CODE_ROUTINE => 'routine',
            self::CODE_RUSH_REPORTING => 'rush reporting',
            self::CODE_STAT => 'stat',
            self::CODE_TIMING_CRITICAL => 'timing critical',
            self::CODE_USE_AS_DIRECTED => 'use as directed',
            self::CODE_URGENT => 'urgent',
        ];

        return @$displays[$code];
    }
}

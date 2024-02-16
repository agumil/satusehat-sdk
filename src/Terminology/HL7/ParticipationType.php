<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ParticipationType implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType';

    const CODE_ADMITTER = 'ADM';
    const CODE_ATTENDER = 'ATND';
    const CODE_CALLBACK_CONTACT = 'CALLBCK';
    const CODE_CONSULTANT = 'CON';
    const CODE_DISCHARGER = 'DIS';
    const CODE_ESCORT = 'ESC';
    const CODE_REFERRER = 'REF';
    const CODE_SECONDARY_PERFORMER = 'SPRF';
    const CODE_PRIMARY_PERFORMER = 'PPRF';
    const CODE_PARTICIPATION = 'PART';

    public static function getCodes(): array
    {
        return [
            self::CODE_ADMITTER,
            self::CODE_ATTENDER,
            self::CODE_CALLBACK_CONTACT,
            self::CODE_CONSULTANT,
            self::CODE_DISCHARGER,
            self::CODE_ESCORT,
            self::CODE_REFERRER,
            self::CODE_SECONDARY_PERFORMER,
            self::CODE_PRIMARY_PERFORMER,
            self::CODE_PARTICIPATION,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ADMITTER => 'admitter',
            self::CODE_ATTENDER => 'attender',
            self::CODE_CALLBACK_CONTACT => 'callback contact',
            self::CODE_CONSULTANT => 'consultant',
            self::CODE_DISCHARGER => 'discharger',
            self::CODE_ESCORT => 'escort',
            self::CODE_REFERRER => 'referrer',
            self::CODE_SECONDARY_PERFORMER => 'secondary performer',
            self::CODE_PRIMARY_PERFORMER => 'primary performer',
            self::CODE_PARTICIPATION => 'Participation',
        ];

        return @$displays[$code];
    }
}

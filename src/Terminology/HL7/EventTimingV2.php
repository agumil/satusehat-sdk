<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EventTimingV2 implements TerminologyInterface
{
    const VERSION = '2.1.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-TimingEvent';

    const CODE_HS = 'HS';
    const CODE_WAKE = 'WAKE';
    const CODE_C = 'C';
    const CODE_CM = 'CM';
    const CODE_CD = 'CD';
    const CODE_CV = 'CV';
    const CODE_AC = 'AC';
    const CODE_ACM = 'ACM';
    const CODE_ACD = 'ACD';
    const CODE_ACV = 'ACV';
    const CODE_PC = 'PC';
    const CODE_PCM = 'PCM';
    const CODE_PCD = 'PCD';
    const CODE_PCV = 'PCV';

    public static function getCodes(): array
    {
        return [
            self::CODE_HS,
            self::CODE_WAKE,
            self::CODE_C,
            self::CODE_CM,
            self::CODE_CD,
            self::CODE_CV,
            self::CODE_AC,
            self::CODE_ACM,
            self::CODE_ACD,
            self::CODE_ACV,
            self::CODE_PC,
            self::CODE_PCM,
            self::CODE_PCD,
            self::CODE_PCV,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_HS => 'HS',
            self::CODE_WAKE => 'WAKE',
            self::CODE_C => 'C',
            self::CODE_CM => 'CM',
            self::CODE_CD => 'CD',
            self::CODE_CV => 'CV',
            self::CODE_AC => 'AC',
            self::CODE_ACM => 'ACM',
            self::CODE_ACD => 'ACD',
            self::CODE_ACV => 'ACV',
            self::CODE_PC => 'PC',
            self::CODE_PCM => 'PCM',
            self::CODE_PCD => 'PCD',
            self::CODE_PCV => 'PCV',
        ];

        return @$displays[$code];
    }
}

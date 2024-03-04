<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class TimingAbbreviationV2 implements TerminologyInterface
{
    const VERSION = '2.1.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-GTSAbbreviation';

    const CODE_BID = 'BID';
    const CODE_TID = 'TID';
    const CODE_QID = 'QID';
    const CODE_AM = 'AM';
    const CODE_PM = 'PM';
    const CODE_QD = 'QD';
    const CODE_QOD = 'QOD';
    const CODE_Q1H = 'Q1H';
    const CODE_Q2H = 'Q2H';
    const CODE_Q3H = 'Q3H';
    const CODE_Q4H = 'Q4H';
    const CODE_Q6H = 'Q6H';
    const CODE_Q8H = 'Q8H';
    const CODE_BED = 'BED';
    const CODE_WK = 'WK';
    const CODE_MO = 'MO';

    public static function getCodes(): array
    {
        return [
            self::CODE_BID,
            self::CODE_TID,
            self::CODE_QID,
            self::CODE_AM,
            self::CODE_PM,
            self::CODE_QD,
            self::CODE_QOD,
            self::CODE_Q1H,
            self::CODE_Q2H,
            self::CODE_Q3H,
            self::CODE_Q4H,
            self::CODE_Q6H,
            self::CODE_Q8H,
            self::CODE_BED,
            self::CODE_WK,
            self::CODE_MO,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_BID => 'BID',
            self::CODE_TID => 'TID',
            self::CODE_QID => 'QID',
            self::CODE_AM => 'AM',
            self::CODE_PM => 'PM',
            self::CODE_QD => 'QD',
            self::CODE_QOD => 'QOD',
            self::CODE_Q1H => 'every hour',
            self::CODE_Q2H => 'every 2 hours',
            self::CODE_Q3H => 'every 3 hours',
            self::CODE_Q4H => 'Q4H',
            self::CODE_Q6H => 'Q6H',
            self::CODE_Q8H => 'every 8 hours',
            self::CODE_BED => 'at bedtime',
            self::CODE_WK => 'weekly',
            self::CODE_MO => 'monthly',
        ];

        return @$displays[$code];
    }
}

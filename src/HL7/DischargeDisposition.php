<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class DischargeDisposition implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/discharge-disposition';

    const CODE_HOME = 'home';
    const CODE_ALT_HOME = 'alt-home';
    const CODE_OTHER_HEALTHCARE = 'other-hcf';
    const CODE_HOSPICE = 'hosp';
    const CODE_LONG_TERM = 'long';
    const CODE_AGAINST_ADVICE = 'aadvice';
    const CODE_EXPIRED = 'exp';
    const CODE_PSYCHIATRIC = 'psy';
    const CODE_REHABILITATION = 'rehab';
    const CODE_NURSING = 'snf';
    const CODE_OTHER = 'oth';

    public static function getCodes(): array
    {
        return [
            self::CODE_HOME,
            self::CODE_ALT_HOME,
            self::CODE_OTHER_HEALTHCARE,
            self::CODE_HOSPICE,
            self::CODE_LONG_TERM,
            self::CODE_AGAINST_ADVICE,
            self::CODE_EXPIRED,
            self::CODE_PSYCHIATRIC,
            self::CODE_REHABILITATION,
            self::CODE_NURSING,
            self::CODE_OTHER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_HOME => 'Home',
            self::CODE_ALT_HOME => 'Alternative home',
            self::CODE_OTHER_HEALTHCARE => 'Other healthcare facility',
            self::CODE_HOSPICE => 'Hospice',
            self::CODE_LONG_TERM => 'Long-term care',
            self::CODE_AGAINST_ADVICE => 'Left against advice',
            self::CODE_EXPIRED => 'Expired',
            self::CODE_PSYCHIATRIC => 'Psychiatric hospital',
            self::CODE_REHABILITATION => 'Rehabilitation',
            self::CODE_NURSING => 'Skilled nursing facility',
            self::CODE_OTHER => 'Other',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\SNOMEDCT;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class Education20 implements TerminologyInterface
{
    const VERSION = '2.0';
    const SYSTEM = 'http://snomed.info/sct';

    const CODE_EDU_CONDITION = '84635008';
    const CODE_EDU_MEDICATION = '967006';
    const CODE_EDU_REHAB = '410082002';
    const CODE_EDU_PAIN = '712651001';
    const CODE_EDU_NUTRITION = '61310001';
    const CODE_EDU_HAND_WASH = '698608004';
    const CODE_EDU_EQUIPMENT = '362978005';

    public static function getCodes(): array
    {
        return [
            self::CODE_EDU_CONDITION,
            self::CODE_EDU_MEDICATION,
            self::CODE_EDU_REHAB,
            self::CODE_EDU_PAIN,
            self::CODE_EDU_NUTRITION,
            self::CODE_EDU_HAND_WASH,
            self::CODE_EDU_EQUIPMENT,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_EDU_CONDITION => 'Disease process or condition education',
            self::CODE_EDU_MEDICATION => 'Medication education',
            self::CODE_EDU_REHAB => 'Rehabilitation therapy education',
            self::CODE_EDU_PAIN => 'Education about pain',
            self::CODE_EDU_NUTRITION => 'Nutrition education',
            self::CODE_EDU_HAND_WASH => 'Hand washing education',
            self::CODE_EDU_EQUIPMENT => 'Medical equipment or device education',
        ];

        return @$displays[$code];
    }
}

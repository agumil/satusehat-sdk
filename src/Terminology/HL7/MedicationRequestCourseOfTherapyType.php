<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class MedicationRequestCourseOfTherapyType implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy';

    const CODE_CONTINUOUS = 'continuous';
    const CODE_ACUTE = 'acute';
    const CODE_SEASONAL = 'seasonal';

    public static function getCodes(): array
    {
        return [
            self::CODE_CONTINUOUS,
            self::CODE_ACUTE,
            self::CODE_SEASONAL,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_CONTINUOUS => 'Continuous long term therapy',
            self::CODE_ACUTE => 'Short course (acute) therapy',
            self::CODE_SEASONAL => 'Seasonal',
        ];

        return @$displays[$code];
    }
}

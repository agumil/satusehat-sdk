<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ObservationCategory implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/observation-category';

    const CODE_SOCIAL_HISTORY = 'social-history';
    const CODE_VITAL_SIGNS = 'vital-signs';
    const CODE_IMAGING = 'imaging';
    const CODE_LABORATORY = 'laboratory';
    const CODE_PROCEDURE = 'procedure';
    const CODE_SURVEY = 'survey';
    const CODE_EXAM = 'exam';
    const CODE_THERAPY = 'therapy';
    const CODE_ACTIVITY = 'activity';

    public static function getCodes(): array
    {
        return [
            self::CODE_SOCIAL_HISTORY,
            self::CODE_VITAL_SIGNS,
            self::CODE_IMAGING,
            self::CODE_LABORATORY,
            self::CODE_PROCEDURE,
            self::CODE_SURVEY,
            self::CODE_EXAM,
            self::CODE_THERAPY,
            self::CODE_ACTIVITY,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_SOCIAL_HISTORY => 'Social History',
            self::CODE_VITAL_SIGNS => 'Vital Signs',
            self::CODE_IMAGING => 'Imaging',
            self::CODE_LABORATORY => 'Laboratory',
            self::CODE_PROCEDURE => 'Procedure',
            self::CODE_SURVEY => 'Survey',
            self::CODE_EXAM => 'Exam',
            self::CODE_THERAPY => 'Therapy',
            self::CODE_ACTIVITY => 'Activity',
        ];

        return @$displays[$code];
    }
}

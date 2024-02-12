<?php
namespace agumil\SatuSehatSDK\Terminology\SNOMEDCT;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ProcedureCategory implements TerminologyInterface
{
    const SYSTEM = 'http://snomed.info/sct';

    const CODE_PSYCHIATRY = '24642003';
    const CODE_COUNSELING = '409063005';
    const CODE_EDUCATION = '409073007';
    const CODE_SURGICAL = '387713003';
    const CODE_DIAGNOSTIC = '103693007';
    const CODE_CHIROPRACTIC = '46947000';
    const CODE_SOCIAL = '410606002';
    const CODE_THERAPEUTIC = '277132007';

    public static function getCodes(): array
    {
        return [
            self::CODE_PSYCHIATRY,
            self::CODE_COUNSELING,
            self::CODE_EDUCATION,
            self::CODE_SURGICAL,
            self::CODE_DIAGNOSTIC,
            self::CODE_CHIROPRACTIC,
            self::CODE_SOCIAL,
            self::CODE_THERAPEUTIC,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PSYCHIATRY => 'Psychiatry procedure or service',
            self::CODE_COUNSELING => 'Counseling',
            self::CODE_EDUCATION => 'Education',
            self::CODE_SURGICAL => 'Surgical procedure',
            self::CODE_DIAGNOSTIC => 'Diagnostic procedure',
            self::CODE_CHIROPRACTIC => 'Chiropractic manipulation',
            self::CODE_SOCIAL => 'Social service procedure',
            self::CODE_THERAPEUTIC => 'Therapeutic procedure',
        ];

        return @$displays[$code];
    }
}

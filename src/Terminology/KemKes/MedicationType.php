<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class MedicationType implements TerminologyInterface
{
    const VERSION = '4.0.1';
    const SYSTEM = 'https://terminology.kemkes.go.id/CodeSystem/tb-case-definition';

    const CODE_NON_COMPOUND = 'NC';
    const CODE_SUCH_DOSES = 'SD';
    const CODE_EQUAL_PARTS = 'EP';

    public static function getCodes(): array
    {
        return [
            self::CODE_NON_COMPOUND,
            self::CODE_SUCH_DOSES,
            self::CODE_EQUAL_PARTS,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_NON_COMPOUND => 'Non-compound',
            self::CODE_SUCH_DOSES => 'Gives of such doses',
            self::CODE_EQUAL_PARTS => 'Divide into equal parts',
        ];

        return @$displays[$code];
    }
}

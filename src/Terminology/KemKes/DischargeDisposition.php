<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class DischargeDisposition implements HL7Interface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/discharge-disposition';

    const CODE_EXPIRED_LT48 = 'exp-lt48h';
    const CODE_EXPIRED_GT48 = 'exp-gt48h';

    public static function getCodes(): array
    {
        return [
            self::CODE_EXPIRED_LT48,
            self::CODE_EXPIRED_GT48,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_EXPIRED_LT48 => 'Meninggal < 48 jam',
            self::CODE_EXPIRED_GT48 => 'Meninggal > 48 jam',
        ];

        return @$displays[$code];
    }
}

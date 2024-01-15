<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class ServiceClassOutpatient implements HL7Interface
{
    const VERSION = '4';
    const SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient';

    const CODE_KELAS_REGULER = 'reguler';
    const CODE_KELAS_EKSEKUTIF = 'eksekutif';

    public static function getCodes(): array
    {
        return [
            self::CODE_KELAS_REGULER,
            self::CODE_KELAS_EKSEKUTIF,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_KELAS_REGULER => 'Perawatan Kelas Reguler',
            self::CODE_KELAS_EKSEKUTIF => 'Perawatan Kelas Eksekutif',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ServiceClassInpatient implements TerminologyInterface
{
    const VERSION = '4';
    const SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient';

    const CODE_KELAS_1 = '1';
    const CODE_KELAS_2 = '2';
    const CODE_KELAS_3 = '3';
    const CODE_KELAS_VIP = 'vip';
    const CODE_KELAS_VVIP = 'vvip';

    public static function getCodes(): array
    {
        return [
            self::CODE_KELAS_1,
            self::CODE_KELAS_2,
            self::CODE_KELAS_3,
            self::CODE_KELAS_VIP,
            self::CODE_KELAS_VVIP,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_KELAS_1 => 'Perawatan Kelas 1',
            self::CODE_KELAS_2 => 'Perawatan Kelas 2',
            self::CODE_KELAS_3 => 'Perawatan Kelas 3',
            self::CODE_KELAS_VIP => 'Perawatan Kelas VIP',
            self::CODE_KELAS_VVIP => 'Perawatan Kelas VVIP',
        ];

        return @$displays[$code];
    }
}

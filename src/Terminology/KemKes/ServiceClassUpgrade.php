<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ServiceClassUpgrade implements TerminologyInterface
{
    const VERSION = '4';
    const SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass';

    const CODE_KELAS_TETAP = 'kelas-tetap';
    const CODE_KELAS_NAIK = 'naik-kelas';
    const CODE_KELAS_TURUN = 'turun-kelas';
    const CODE_KELAS_TITIP = 'titip-rawat';

    public static function getCodes(): array
    {
        return [
            self::CODE_KELAS_TETAP,
            self::CODE_KELAS_NAIK,
            self::CODE_KELAS_TURUN,
            self::CODE_KELAS_TITIP,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_KELAS_TETAP => 'Kelas Tetap Perawatan',
            self::CODE_KELAS_NAIK => 'Kenaikan Kelas Perawatan',
            self::CODE_KELAS_TURUN => 'Penurunan Kelas Perawatan',
            self::CODE_KELAS_TITIP => 'Titip Kelas Perawatan',
        ];

        return @$displays[$code];
    }
}

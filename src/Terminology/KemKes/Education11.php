<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class Education11 implements TerminologyInterface
{
    const VERSION = '1.1';
    const SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/clinical-term';

    const CODE_EDU_PROGNOSIS = 'ED000001';
    const CODE_EDU_ALT_ACT_RISK = 'ED000002';
    const CODE_EDU_PREGNANCY_RISK = 'ED000008';
    const CODE_EDU_IMD_ASI = 'ED000009';
    const CODE_EDU_PHBS = 'ED000010';
    const CODE_EDU_KB = 'ED000011';
    const CODE_EDU_OTHER = 'ED000012';
    const CODE_EDU_TALI_PUSAT = 'PC000001';
    const CODE_EDU_SALEP_MATA = 'PC000002';
    const CODE_EDU_MTBM = 'PC000003';

    public static function getCodes(): array
    {
        return [
            self::CODE_EDU_PROGNOSIS,
            self::CODE_EDU_ALT_ACT_RISK,
            self::CODE_EDU_PREGNANCY_RISK,
            self::CODE_EDU_IMD_ASI,
            self::CODE_EDU_PHBS,
            self::CODE_EDU_KB,
            self::CODE_EDU_OTHER,
            self::CODE_EDU_TALI_PUSAT,
            self::CODE_EDU_SALEP_MATA,
            self::CODE_EDU_MTBM,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_EDU_PROGNOSIS => 'Edukasi Prognosis',
            self::CODE_EDU_ALT_ACT_RISK => 'Edukasi terkait Alternatif Tindakan dan Resikonya',
            self::CODE_EDU_PREGNANCY_RISK => 'Edukasi Tanda Bahaya Kehamilan, Bersalin dan Nifas',
            self::CODE_EDU_IMD_ASI => 'Edukasi IMD dan ASI Eksklusif',
            self::CODE_EDU_PHBS => 'Edukasi PHBS',
            self::CODE_EDU_KB => 'Edukasi KB pasca salin',
            self::CODE_EDU_OTHER => 'Edukasi lainnya',
            self::CODE_EDU_TALI_PUSAT => 'Perawatan tali pusat',
            self::CODE_EDU_SALEP_MATA => 'Pemberian salep antibiotik mata',
            self::CODE_EDU_MTBM => 'Manajemen Terpadu Bayi Muda (MTBM)',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\Terminology\KemKes;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class EncounterStatus implements TerminologyInterface
{
    const VERSION = '4';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0203';

    const CODE_PLANNED = 'planned';
    const CODE_ARRIVED = 'arrived';
    const CODE_TRIAGED = 'triaged';
    const CODE_IN_PROGRESS = 'in-progress';
    const CODE_ON_LEAVE = 'onleave';
    const CODE_FINISHED = 'finished';
    const CODE_CANCELLED = 'cancelled';

    public static function getCodes(): array
    {
        return [
            self::CODE_PLANNED,
            self::CODE_ARRIVED,
            self::CODE_TRIAGED,
            self::CODE_IN_PROGRESS,
            self::CODE_ON_LEAVE,
            self::CODE_FINISHED,
            self::CODE_CANCELLED,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PLANNED => 'Sudah Direncanakan',
            self::CODE_ARRIVED => 'Sudah Datang',
            self::CODE_TRIAGED => 'Triase',
            self::CODE_IN_PROGRESS => 'Sedang Berlangsung',
            self::CODE_ON_LEAVE => 'Sedang Pergi',
            self::CODE_FINISHED => 'Sudah Selesai',
            self::CODE_CANCELLED => 'Dibatalkan',
        ];

        return @$displays[$code];
    }
}

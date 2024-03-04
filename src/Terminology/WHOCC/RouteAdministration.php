<?php
namespace agumil\SatuSehatSDK\Terminology\WHOCC;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class RouteAdministration implements TerminologyInterface
{
    const VERSION = '1';
    const SYSTEM = 'http://www.whocc.no/atc';

    const CODE_IMPLANT = 'Implant';
    const CODE_INHALATION = 'Inhal';
    const CODE_INSTILLATION = 'Instill';
    const CODE_NASAL = 'N';
    const CODE_ORAL = 'O';
    const CODE_PARENTERAL = 'P';
    const CODE_RECTAL = 'R';
    const CODE_SUBLINGUAL_BUCCAL = 'SL';
    const CODE_TRANSDERMAL = 'TD';
    const CODE_Vaginal = 'V';

    public static function getCodes(): array
    {
        return [
            self::CODE_IMPLANT,
            self::CODE_INHALATION,
            self::CODE_INSTILLATION,
            self::CODE_NASAL,
            self::CODE_ORAL,
            self::CODE_PARENTERAL,
            self::CODE_RECTAL,
            self::CODE_SUBLINGUAL_BUCCAL,
            self::CODE_TRANSDERMAL,
            self::CODE_Vaginal,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_IMPLANT => 'Implant',
            self::CODE_INHALATION => 'Inhalation',
            self::CODE_INSTILLATION => 'Instillation',
            self::CODE_NASAL => 'Nasal',
            self::CODE_ORAL => 'Oral',
            self::CODE_PARENTERAL => 'Parenteral',
            self::CODE_RECTAL => 'Rectal',
            self::CODE_SUBLINGUAL_BUCCAL => 'Sublingual/Buccal/Oromucosal',
            self::CODE_TRANSDERMAL => 'Transdermal',
            self::CODE_Vaginal => 'Vaginal',
        ];

        return @$displays[$code];
    }
}

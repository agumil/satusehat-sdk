<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class LocationType implements HL7Interface
{
    const VERSION = '5.4.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/location-physical-type';

    const CODE_SITE = 'si';
    const CODE_BUILDING = 'bu';
    const CODE_WING = 'wi';
    const CODE_WARD = 'wa';
    const CODE_LEVEL = 'lvl';
    const CODE_CORRIDOR = 'co';
    const CODE_ROOM = 'ro';
    const CODE_BED = 'bd';
    const CODE_VEHICLE = 've';
    const CODE_HOUSE = 'ho';
    const CODE_CABINET = 'ca';
    const CODE_ROAD = 'rd';
    const CODE_AREA = 'area';
    const CODE_JURISDICTION = 'jdn';
    const CODE_VIRTUAL = 'vi';

    public static function getCodes(): array
    {
        return [
            self::CODE_SITE,
            self::CODE_BUILDING,
            self::CODE_WING,
            self::CODE_WARD,
            self::CODE_LEVEL,
            self::CODE_CORRIDOR,
            self::CODE_ROOM,
            self::CODE_BED,
            self::CODE_VEHICLE,
            self::CODE_HOUSE,
            self::CODE_CABINET,
            self::CODE_ROAD,
            self::CODE_AREA,
            self::CODE_JURISDICTION,
            self::CODE_VIRTUAL,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_SITE => 'Site',
            self::CODE_BUILDING => 'Building',
            self::CODE_WING => 'Wing',
            self::CODE_WARD => 'Ward',
            self::CODE_LEVEL => 'Level',
            self::CODE_CORRIDOR => 'Corridor',
            self::CODE_ROOM => 'Room',
            self::CODE_BED => 'Bed',
            self::CODE_VEHICLE => 'Vehicle',
            self::CODE_HOUSE => 'House',
            self::CODE_CABINET => 'Cabinet',
            self::CODE_ROAD => 'Road',
            self::CODE_AREA => 'Area',
            self::CODE_JURISDICTION => 'Jurisdiction',
            self::CODE_VIRTUAL => 'Virtual',
        ];

        return @$displays[$code];
    }
}

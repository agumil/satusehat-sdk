<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class Diet implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/diet';

    const CODE_VEGETARIAN = 'vegetarian';
    const CODE_DAIRY_FREE = 'dairy-free';
    const CODE_NUT_FREE = 'nut-free';
    const CODE_GLUTEN_FREE = 'gluten-free';
    const CODE_VEGAN = 'vegan';
    const CODE_HALAL = 'halal';
    const CODE_KOSHER = 'kosher';

    public static function getCodes(): array
    {
        return [
            self::CODE_VEGETARIAN,
            self::CODE_DAIRY_FREE,
            self::CODE_NUT_FREE,
            self::CODE_GLUTEN_FREE,
            self::CODE_VEGAN,
            self::CODE_HALAL,
            self::CODE_KOSHER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_VEGETARIAN => 'Vegetarian',
            self::CODE_DAIRY_FREE => 'Dairy Free',
            self::CODE_NUT_FREE => 'Nut Free',
            self::CODE_GLUTEN_FREE => 'GLuten Free',
            self::CODE_VEGAN => 'Vegan',
            self::CODE_HALAL => 'Halal',
            self::CODE_KOSHER => 'Kosher',
        ];

        return @$displays[$code];
    }
}

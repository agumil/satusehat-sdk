<?php

use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\Extension;
use agumil\SatuSehatSDK\DataType\ExtensionExtended;
use agumil\SatuSehatSDK\DataType\Period;

if (!function_exists('ss_address')) {
    function ss_address(string $use, string $type, array $lines, ?string $district = null, ?string $city = null, ?string $state = null, ?string $postalCode = null, ?string $country = null, ?Period $period = null): Address
    {
        return new Address($use, $type, $lines, $district, $city, $state, $postalCode, $country, $period);
    }
}

if (!function_exists('ss_address_extension')) {
    function ss_address_extension(int $provinceCode, ?int $cityCode = null, ?int $districtCode = null, ?int $villageCode = null, ?int $rt = null, ?int $rw = null): ExtensionExtended
    {
        $extensions[] = new Extension('province', (string) $provinceCode, 'Code');

        if (isset($cityCode)) {
            $extensions[] = new Extension('city', (string) $cityCode, 'Code');
        }

        if (isset($districtCode)) {
            $extensions[] = new Extension('city', (string) $districtCode, 'Code');
        }

        if (isset($villageCode)) {
            $extensions[] = new Extension('city', (string) $villageCode, 'Code');
        }

        if (isset($rt)) {
            $extensions[] = new Extension('city', (string) $rt, 'Code');
        }

        if (isset($rw)) {
            $extensions[] = new Extension('city', (string) $rw, 'Code');
        }

        return new ExtensionExtended(
            'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
            ...$extensions
        );
    }
}

<?php

use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;
use agumil\SatuSehatSDK\DataType\Period;

if (!function_exists('ss_address')) {
    function ss_address(string $use, string $type, array $lines, ?string $district = null, ?string $city = null, ?string $state = null, ?string $postalCode = null, ?string $country = null, ?Period $period = null): Address
    {
        return new Address($use, $type, $lines, $district, $city, $state, $postalCode, $country, $period);
    }
}

if (!function_exists('ss_address_extension')) {
    function ss_address_extension(?int $provinceCode = null, ?int $cityCode = null, ?int $districtCode = null, ?int $villageCode = null, ?int $rt = null, ?int $rw = null): ExtensionAdministrativeCode
    {
        return new ExtensionAdministrativeCode($provinceCode, $cityCode, $districtCode, $villageCode, $rt, $rw);
    }
}

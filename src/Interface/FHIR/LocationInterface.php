<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface LocationInterface
{
    public function getLocation(array $params = []);
    public function getLocationById(string $id);
    public function createLocation($params);
    public function updateLocation(string $id, $params);
}

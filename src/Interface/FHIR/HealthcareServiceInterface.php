<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface HealthcareServiceInterface
{
    public function getHealthcareService(array $params = []);
    public function getHealthcareServiceById(string $id);
    public function createHealthcareService($params);
    public function updateHealthcareService(string $id, $params);
}

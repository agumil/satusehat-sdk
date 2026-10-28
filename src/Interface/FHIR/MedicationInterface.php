<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface MedicationInterface
{
    public function getMedication(array $params = []);
    public function getMedicationById(string $id);
    public function createMedication($params);
    public function updateMedication(string $id, $params);
}

<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface MedicationRequestInterface
{
    public function getMedicationRequest(array $params = []);
    public function getMedicationRequestById(string $id);
    public function createMedicationRequest($params);
    public function updateMedicationRequest(string $id, $params);
}

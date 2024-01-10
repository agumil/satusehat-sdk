<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface MedicationDispenseInterface
{
    public function getMedicationDispense(array $params = []);
    public function getMedicationDispenseById(string $id);
    public function createMedicationDispense($params);
    public function updateMedicationDispense(string $id, $params);
}

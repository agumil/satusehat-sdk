<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface PatientInterface
{
    public function getPatient(array $params = []);
    public function getPatientById(string $id);
    public function createPatient($params);
}

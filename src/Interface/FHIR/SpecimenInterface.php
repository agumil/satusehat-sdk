<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface SpecimenInterface
{
    public function getSpecimen(array $params = []);
    public function getSpecimenById(string $id);
    public function createSpecimen($params);
    public function updateSpecimen(string $id, $params);
}

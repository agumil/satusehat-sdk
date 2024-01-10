<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ImmunizationInterface
{
    public function getImmunization(array $params = []);
    public function getImmunizationById(string $id);
    public function createImmunization($params);
    public function updateImmunization(string $id, $params);
}

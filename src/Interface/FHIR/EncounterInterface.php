<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface EncounterInterface
{
    public function getEncounter(array $params = []);
    public function getEncounterById(string $id);
    public function createEncounter($params);
    public function updateEncounter(string $id, $params);
}

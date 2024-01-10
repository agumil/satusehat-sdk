<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface PractitionerRoleInterface
{
    public function getPractitionerRole(array $params = []);
    public function getPractitionerRoleById(string $id);
    public function createPractitionerRole($params);
    public function updatePractitionerRole(string $id, $params);
}

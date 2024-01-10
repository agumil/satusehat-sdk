<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface PractitionerInterface
{
    public function getPractitioner(array $params = []);
    public function getPractitionerById(string $id);
}

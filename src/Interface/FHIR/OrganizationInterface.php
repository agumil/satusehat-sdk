<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface OrganizationInterface
{
    public function getOrganization(array $params = []);
    public function getOrganizationById(string $id);
    public function createOrganization($params);
    public function updateOrganization(string $id, $params);
}

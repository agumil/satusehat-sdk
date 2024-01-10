<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ServiceRequestInterface
{
    public function getServiceRequest(array $params = []);
    public function getServiceRequestById(string $id);
    public function createServiceRequest($params);
    public function updateServiceRequest(string $id, $params);
}

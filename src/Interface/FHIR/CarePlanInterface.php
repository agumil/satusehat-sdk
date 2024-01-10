<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface CarePlanInterface
{
    public function getCarePlan(array $params = []);
    public function getCarePlanById(string $id);
    public function createCarePlan($params);
    public function updateCarePlan(string $id, $params);
}

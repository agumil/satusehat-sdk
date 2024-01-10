<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface AllergyIntoleranceInterface
{
    public function getAllergyIntolerance(array $params = []);
    public function getAllergyIntoleranceById(string $id);
    public function createAllergyIntolerance($params);
    public function updateAllergyIntolerance(string $id, $params);
}

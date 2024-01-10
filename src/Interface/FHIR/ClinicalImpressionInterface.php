<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ClinicalImpressionInterface
{
    public function getClinicalImpression(array $params = []);
    public function getClinicalImpressionById(string $id);
    public function createClinicalImpression($params);
    public function updateClinicalImpression(string $id, $params);
}

<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ConditionInterface
{
    public function getCondition(array $params = []);
    public function getConditionById(string $id);
    public function createCondition($params);
    public function updateCondition(string $id, $params);
}

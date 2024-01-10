<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ProcedureInterface
{
    public function getProcedure(array $params = []);
    public function getProcedureById(string $id);
    public function createProcedure($params);
    public function updateProcedure(string $id, $params);
}

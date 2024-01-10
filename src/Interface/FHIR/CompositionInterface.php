<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface CompositionInterface
{
    public function getComposition(array $params = []);
    public function getCompositionById(string $id);
    public function createComposition($params);
    public function updateComposition(string $id, $params);
}

<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ObservationInterface
{
    public function getObservation(array $params = []);
    public function getObservationById(string $id);
    public function createObservation($params);
    public function updateObservation(string $id, $params);
}

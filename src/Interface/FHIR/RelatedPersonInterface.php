<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface RelatedPersonInterface
{
    public function getRelatedPerson(array $params = []);
    public function getRelatedPersonById(string $id);
    public function createRelatedPerson($params);
    public function updateRelatedPerson(string $id, $params);
}

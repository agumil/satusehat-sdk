<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface EpisodeOfCareInterface
{
    public function getEpisodeOfCare(array $params = []);
    public function getEpisodeOfCareById(string $id);
    public function createEpisodeOfCare($params);
    public function updateEpisodeOfCare(string $id, $params);
}

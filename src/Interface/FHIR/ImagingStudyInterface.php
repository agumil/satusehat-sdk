<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface ImagingStudyInterface
{
    public function getImagingStudy(array $params = []);
    public function getImagingStudyById(string $id);
    public function createImagingStudy($params);
    public function updateImagingStudy(string $id, $params);
}

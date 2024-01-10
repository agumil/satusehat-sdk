<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface QuestionnaireResponseInterface
{
    public function getQuestionnaireResponse(array $params = []);
    public function getQuestionnaireResponseById(string $id);
    public function createQuestionnaireResponse($params);
    public function updateQuestionnaireResponse(string $id, $params);
}

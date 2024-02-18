<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\QuestionnaireResponseInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class QuestionnaireResponse extends Base implements QuestionnaireResponseInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getQuestionnaireResponse(array $params = [])
    {
        $url = $this->base_url . 'QuestionnaireResponse';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getQuestionnaireResponseById(string $id)
    {
        $url = $this->base_url . "QuestionnaireResponse/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createQuestionnaireResponse($params)
    {
        $url = $this->base_url . 'QuestionnaireResponse';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateQuestionnaireResponse(string $id, $params)
    {
        $url = $this->base_url . "QuestionnaireResponse/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

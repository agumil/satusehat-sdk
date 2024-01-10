<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\AllergyIntoleranceInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class AllergyIntolerance extends Base implements AllergyIntoleranceInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getAllergyIntolerance(array $params = [])
    {
        $url = $this->base_url . 'AllergyIntolerance';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getAllergyIntoleranceById(string $id)
    {
        $url = $this->base_url . "AllergyIntolerance/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createAllergyIntolerance($params)
    {
        $url = $this->base_url . 'AllergyIntolerance';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateAllergyIntolerance(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "AllergyIntolerance/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

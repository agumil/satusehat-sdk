<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\CarePlanInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class CarePlan extends Base implements CarePlanInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getCarePlan(array $params = [])
    {
        $url = $this->base_url . 'CarePlan';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getCarePlanById(string $id)
    {
        $url = $this->base_url . "CarePlan/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createCarePlan($params)
    {
        $url = $this->base_url . 'CarePlan';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateCarePlan(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "CarePlan/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

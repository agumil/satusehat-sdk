<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\ObservationInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Observation extends Base implements ObservationInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getObservation(array $params = [])
    {
        $url = $this->base_url . 'Observation';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getObservationById(string $id)
    {
        $url = $this->base_url . "Observation/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createObservation($params)
    {
        $url = $this->base_url . 'Observation';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateObservation(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "Observation/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

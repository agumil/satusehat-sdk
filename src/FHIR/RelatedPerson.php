<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\RelatedPersonInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class RelatedPerson extends Base implements RelatedPersonInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getRelatedPerson(array $params = [])
    {
        $url = $this->base_url . 'RelatedPerson';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getRelatedPersonById(string $id)
    {
        $url = $this->base_url . "RelatedPerson/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createRelatedPerson($params)
    {
        $url = $this->base_url . 'RelatedPerson';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateRelatedPerson(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "RelatedPerson/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

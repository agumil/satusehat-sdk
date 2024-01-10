<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\OrganizationInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Organization extends Base implements OrganizationInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getOrganization(array $params = [])
    {
        $url = $this->base_url . 'Organization';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getOrganizationById(string $id)
    {
        $url = $this->base_url . "Organization/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createOrganization($params)
    {
        $url = $this->base_url . 'Organization';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateOrganization(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "Organization/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

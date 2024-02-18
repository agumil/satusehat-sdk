<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\PractitionerRoleInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class PractitionerRole extends Base implements PractitionerRoleInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getPractitionerRole(array $params = [])
    {
        $url = $this->base_url . 'PractitionerRole';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getPractitionerRoleById(string $id)
    {
        $url = $this->base_url . "PractitionerRole/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createPractitionerRole($params)
    {
        $url = $this->base_url . 'PractitionerRole';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updatePractitionerRole(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "PractitionerRole/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

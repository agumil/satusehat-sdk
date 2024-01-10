<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\ServiceRequestInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class ServiceRequest extends Base implements ServiceRequestInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getServiceRequest(array $params = [])
    {
        $url = $this->base_url . 'ServiceRequest';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getServiceRequestById(string $id)
    {
        $url = $this->base_url . "ServiceRequest/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createServiceRequest($params)
    {
        $url = $this->base_url . 'ServiceRequest';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateServiceRequest(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "ServiceRequest/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

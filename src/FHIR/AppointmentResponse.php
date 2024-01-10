<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\AppointmentResponseInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class AppointmentResponse extends Base implements AppointmentResponseInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getAppointmentResponse(array $params = [])
    {
        $url = $this->base_url . 'AppointmentResponse';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getAppointmentResponseById(string $id)
    {
        $url = $this->base_url . "AppointmentResponse/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createAppointmentResponse($params)
    {
        $url = $this->base_url . 'AppointmentResponse';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateAppointmentResponse(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "AppointmentResponse/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

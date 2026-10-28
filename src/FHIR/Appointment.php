<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\FHIR\AppointmentInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Appointment extends Base implements AppointmentInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getAppointment(array $params = [])
    {
        $url = $this->base_url . 'Appointment';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getAppointmentById(string $id)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "Appointment/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createAppointment($params)
    {
        $url = $this->base_url . 'Appointment';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateAppointment(string $id, $params)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $params['id'] = $id;

        $url = $this->base_url . "Appointment/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

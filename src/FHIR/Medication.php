<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\FHIR\MedicationInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Medication extends Base implements MedicationInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getMedication(array $params = [])
    {
        $url = $this->base_url . 'Medication';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getMedicationById(string $id)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "Medication/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createMedication($params)
    {
        $url = $this->base_url . 'Medication';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateMedication(string $id, $params)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $params['id'] = $id;

        $url = $this->base_url . "Medication/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

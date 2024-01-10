<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\MedicationDispenseInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class MedicationDispense extends Base implements MedicationDispenseInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getMedicationDispense(array $params = [])
    {
        $url = $this->base_url . 'MedicationDispense';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getMedicationDispenseById(string $id)
    {
        $url = $this->base_url . "MedicationDispense/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createMedicationDispense($params)
    {
        $url = $this->base_url . 'MedicationDispense';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateMedicationDispense(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "MedicationDispense/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

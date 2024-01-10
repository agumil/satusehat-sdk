<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\MedicationRequestInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class MedicationRequest extends Base implements MedicationRequestInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getMedicationRequest(array $params = [])
    {
        $url = $this->base_url . 'MedicationRequest';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getMedicationRequestById(string $id)
    {
        $url = $this->base_url . "MedicationRequest/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createMedicationRequest($params)
    {
        $url = $this->base_url . 'MedicationRequest';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateMedicationRequest(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "MedicationRequest/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

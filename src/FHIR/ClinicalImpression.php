<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\ClinicalImpressionInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class ClinicalImpression extends Base implements ClinicalImpressionInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getClinicalImpression(array $params = [])
    {
        $url = $this->base_url . 'ClinicalImpression';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getClinicalImpressionById(string $id)
    {
        $url = $this->base_url . "ClinicalImpression/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createClinicalImpression($params)
    {
        $url = $this->base_url . 'ClinicalImpression';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateClinicalImpression(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "ClinicalImpression/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

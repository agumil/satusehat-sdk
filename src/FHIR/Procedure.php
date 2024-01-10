<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\ProcedureInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Procedure extends Base implements ProcedureInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getProcedure(array $params = [])
    {
        $url = $this->base_url . 'Procedure';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url, $params));
    }

    public function getProcedureById(string $id)
    {
        $url = $this->base_url . "Procedure/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->get($url));
    }

    public function createProcedure($params)
    {
        $url = $this->base_url . 'Procedure';
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateProcedure(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "Procedure/{$id}";
        $httpRequest = new HttpRequest($this->oauth2);

        return new Response($httpRequest->put($url, $params));
    }
}

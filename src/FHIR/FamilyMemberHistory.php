<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\FamilyMemberHistoryInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class FamilyMemberHistory extends Base implements FamilyMemberHistoryInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getFamilyMemberHistory(array $params = [])
    {
        $url = $this->base_url . 'FamilyMemberHistory';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getFamilyMemberHistoryById(string $id)
    {
        $url = $this->base_url . "FamilyMemberHistory/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createFamilyMemberHistory($params)
    {
        $url = $this->base_url . 'FamilyMemberHistory';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateFamilyMemberHistory(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "FamilyMemberHistory/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

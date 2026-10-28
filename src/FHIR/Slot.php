<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\FHIR\SlotInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Slot extends Base implements SlotInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getSlot(array $params = [])
    {
        $url = $this->base_url . 'Slot';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getSlotById(string $id)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "Slot/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createSlot($params)
    {
        $url = $this->base_url . 'Slot';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateSlot(string $id, $params)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $params['id'] = $id;

        $url = $this->base_url . "Slot/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

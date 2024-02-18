<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\EpisodeOfCareInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class EpisodeOfCare extends Base implements EpisodeOfCareInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getEpisodeOfCare(array $params = [])
    {
        $url = $this->base_url . 'EpisodeOfCare';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getEpisodeOfCareById(string $id)
    {
        $url = $this->base_url . "EpisodeOfCare/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createEpisodeOfCare($params)
    {
        $url = $this->base_url . 'EpisodeOfCare';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateEpisodeOfCare(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "EpisodeOfCare/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\FHIR\ImagingStudyInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class ImagingStudy extends Base implements ImagingStudyInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getImagingStudy(array $params = [])
    {
        $url = $this->base_url . 'ImagingStudy';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getImagingStudyById(string $id)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "ImagingStudy/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createImagingStudy($params)
    {
        $url = $this->base_url . 'ImagingStudy';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateImagingStudy(string $id, $params)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $params['id'] = $id;

        $url = $this->base_url . "ImagingStudy/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

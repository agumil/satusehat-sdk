<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Interface\FHIR\DiagnosticReportInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class DiagnosticReport extends Base implements DiagnosticReportInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getDiagnosticReport(array $params = [])
    {
        $url = $this->base_url . 'DiagnosticReport';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getDiagnosticReportById(string $id)
    {
        $url = $this->base_url . "DiagnosticReport/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createDiagnosticReport($params)
    {
        $url = $this->base_url . 'DiagnosticReport';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    public function updateDiagnosticReport(string $id, $params)
    {
        $params['id'] = $id;

        $url = $this->base_url . "DiagnosticReport/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->put($url, $params));
    }
}

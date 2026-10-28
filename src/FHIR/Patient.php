<?php
namespace agumil\SatuSehatSDK\FHIR;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Interface\FHIR\PatientInterface;
use agumil\SatuSehatSDK\Request\HttpRequest;
use agumil\SatuSehatSDK\Response\Response;

class Patient extends Base implements PatientInterface
{
    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        parent::__construct($oauth2, $config);
    }

    public function getPatient(array $params = [])
    {
        $url = $this->base_url . 'Patient';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url, $params));
    }

    public function getPatientById(string $id)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "Patient/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->get($url));
    }

    public function createPatient($params)
    {
        $url = $this->base_url . 'Patient';
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->post($url, $params));
    }

    /**
     * Update parsial memakai JSON Patch (RFC 6902). SATUSEHAT hanya menyediakan
     * PATCH untuk Patient — tidak ada PUT seperti pada resource lain.
     *
     * @param array $params Daftar operasi, mis.
     *                      [['op' => 'replace', 'path' => '/language', 'value' => 'id']]
     */
    public function updatePatient(string $id, array $params)
    {
        $id = ValidatorHelper::resourceId('id', $id);
        $url = $this->base_url . "Patient/{$id}";
        $httpRequest = new HttpRequest($this->oauth2, $this->config);

        return new Response($httpRequest->patch($url, $params));
    }
}

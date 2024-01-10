<?php
namespace agumil\SatuSehatSDK\FHIR;

interface ConsentInterface
{
    public function getConsent(array $params = []);
    public function updateConsentRaw(string $id, $params);
}

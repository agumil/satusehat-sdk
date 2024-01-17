<?php
namespace agumil\SatuSehatSDK\Interface\Consent;

interface ConsentInterface
{
    public function getConsent(array $params = []);
    public function updateConsentRaw(string $id, $params);
}

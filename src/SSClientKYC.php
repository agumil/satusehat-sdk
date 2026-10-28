<?php
namespace agumil\SatuSehatSDK;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\KYC\Kyc;

class SSClientKYC
{
    private Oauth2 $oauth2;

    private array $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        $this->oauth2 = $oauth2;
        $this->config = $config;
    }

    public function generateUrl(string $encryptedMessage)
    {
        return (new Kyc($this->oauth2, $this->config))->generateUrl($encryptedMessage);
    }

    public function generateChallengeCode(array $params, string $urlToken, bool $debugMode = false)
    {
        return (new Kyc($this->oauth2, $this->config))->generateChallengeCode($params, $urlToken, $debugMode);
    }

    public function requestPerNik(string $nik, string $name, string $urlToken, bool $debugMode = false)
    {
        return (new Kyc($this->oauth2, $this->config))->requestPerNik($nik, $name, $urlToken, $debugMode);
    }
}

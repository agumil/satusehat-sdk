<?php
namespace agumil\SatuSehatSDK\Interface\KYC;

use agumil\SatuSehatSDK\Auth\Oauth2;

interface KycInterface
{
    public function __construct(Oauth2 $oauth2, array $config = []);
    public function generateUrl(string $encryptedMessage);
    public function generateChallengeCode(array $params, string $urlToken, bool $debugMode = false);
    public function requestPerNik(string $nik, string $name, string $urlToken, bool $debugMode = false);
}

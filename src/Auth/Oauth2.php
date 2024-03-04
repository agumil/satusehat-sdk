<?php
namespace agumil\SatuSehatSDK\Auth;

use agumil\SatuSehatSDK\Config\Endpoint;
use agumil\SatuSehatSDK\Exception\SSEnvException;
use agumil\SatuSehatSDK\Exception\SSOauth2Exception;
use agumil\SatuSehatSDK\Helper\EnvHelper;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\Response\Response;
use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Oauth2
{
    private $base_url;

    private $organization_id;

    private $client_id;

    private $client_secret;

    private $token = null;

    private $token_expired_at = 0;

    public function __construct(array $config = [])
    {
        if (EnvHelper::isStaging() || $config['environment'] === 'staging') {
            $this->base_url = Endpoint::STG_OAUTH2;
        } elseif (EnvHelper::isProduction() || $config['environment'] === 'production') {
            $this->base_url = Endpoint::PROD_OAUTH2;
        } else {
            $env = EnvHelper::ENV;
            throw new SSEnvException("Oauth2 - Environment '{$env}' OR Parameter 'environment' must be provided. Valid environment value is must be 'staging' or 'production'.");
        }

        if (isset($config['organization_id'])) {
            $this->organization_id = $config['organization_id'];
        } else {
            $this->organization_id = EnvHelper::getOrganizationId();
        }

        // if (empty($this->organization_id)) {
        //     $env_organizationid = EnvHelper::ORGANIZATION_ID;
        //     throw new SSEnvException("Oauth2 - Environment '{$env_organizationid}' OR Configuration 'organization_id' must be provided.");
        // }

        if (isset($config['client_id'])) {
            $this->client_id = $config['client_id'];
        } else {
            $this->client_id = EnvHelper::getClientId();
        }

        if (empty($this->client_id)) {
            $env_clientid = EnvHelper::CLIENT_ID;
            throw new SSEnvException("Oauth2 - Environment '{$env_clientid}' OR Configuration 'client_id' must be provided.");
        }

        if (isset($config['client_secret'])) {
            $this->client_secret = $config['client_secret'];
        } else {
            $this->client_secret = EnvHelper::getClientSecret();
        }

        if (empty($this->client_secret)) {
            $env_clientsecret = EnvHelper::CLIENT_SECRET;
            throw new SSEnvException("Oauth2 - Environment '{$env_clientsecret}' OR Configuration 'client_secret' must be provided.");
        }
    }

    public function getContent()
    {
        return $this->generateToken()->getContent();
    }

    public function getToken()
    {
        $now = (new DateTime('now'))->getTimestamp();
        if ($now >= $this->token_expired_at) {
            $response = $this->generateToken();
            if ($response->getHttpStatus() !== $response::STATUS_2XX) {
                throw new SSOauth2Exception($response->getContent());
            }

            $response = json_decode($response->getContent());
            $accessToken = $response->access_token;
            $expiresIn = $response->expires_in - 60;
            $expiredAt = (new DateTime('now'))->add(new DateInterval("PT{$expiresIn}S"))->getTimestamp();

            $this->token = $accessToken;
            $this->token_expired_at = $expiredAt;
        }

        return $this->token;
    }

    private function generateToken()
    {
        $url = StrHelper::appendSlash($this->base_url) . 'accesstoken';
        try {
            $guzzle = new Client();
            $options['query']['grant_type'] = 'client_credentials';
            $options['form_params']['client_id'] = $this->client_id;
            $options['form_params']['client_secret'] = $this->client_secret;
            $response = $guzzle->post($url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return new Response($response);
    }

    public function getOrganizationId()
    {
        return $this->organization_id;
    }
}

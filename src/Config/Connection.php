<?php
namespace agumil\SatuSehatSDK\Config;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class Connection
{
    private string $environment;

    private string $organization_id;

    private string $client_id;

    private string $client_secret;

    private int $timeout = 60;

    public function __construct(array $config)
    {
        ValidatorHelper::required('environment', @$config['environment']);
        ValidatorHelper::required('organization_id', @$config['organization_id']);
        ValidatorHelper::required('client_id', @$config['client_id']);
        ValidatorHelper::required('client_secret', @$config['client_secret']);

        $this->environment = $config['environment'];
        $this->organization_id = $config['organization_id'];
        $this->client_id = $config['client_id'];
        $this->client_secret = $config['client_secret'];

        if (isset($config['timeout'])) {
            ValidatorHelper::unsignedInt('timeout', $config['timeout']);

            $this->timeout = (int) $config['timeout'];
        }
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getOrganizationId()
    {
        return $this->organization_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getClientSecret()
    {
        return $this->client_secret;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }
}

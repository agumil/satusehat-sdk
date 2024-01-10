<?php
namespace agumil\SatuSehatSDK\Builder;

class PayloadBuilderServiceRequest
{
    private static $resource_type = 'ServiceRequest';

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

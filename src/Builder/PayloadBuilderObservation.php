<?php
namespace agumil\SatuSehatSDK\Builder;

class PayloadBuilderObservation
{
    private static $resource_type = 'Observation';

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

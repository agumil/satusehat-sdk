<?php
namespace agumil\SatuSehatSDK\Builder;

class PayloadBuilderEncounter
{
    private static $resource_type = 'Encounter';

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

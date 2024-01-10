<?php
namespace agumil\SatuSehatSDK\Builder;

class PayloadBuilderComposition
{
    private static $resource_type = 'Composition';

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

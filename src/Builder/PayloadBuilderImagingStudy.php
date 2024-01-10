<?php
namespace agumil\SatuSehatSDK\Builder;

class PayloadBuilderImagingStudy
{
    private static $resource_type = 'ImagingStudy';

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

<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class ExtensionTransportedTime
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime';

    private string $dateTime;

    public function __construct(string $dateTime)
    {
        $this->dateTime = ValidatorHelper::dateTime($dateTime);
    }

    public function toArray(): array
    {
        return (new Extension(self::$url, $this->dateTime, 'DateTime'))->toArray();
    }
}

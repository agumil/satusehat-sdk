<?php
namespace agumil\SatuSehatSDK\DataType;

class ExtensionBirthPlace
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace';

    private Address $address;

    private ?ExtensionAdministrativeCode $administrativeCode;

    public function __construct(Address $address, ?ExtensionAdministrativeCode $administrativeCode = null)
    {
        $this->address = $address;
        $this->administrativeCode = $administrativeCode;
    }

    public function toArray(): array
    {
        $address = $this->address->toArray();

        if (isset($this->administrativeCode)) {
            $address['extension'][] = $this->administrativeCode->toArray();
        }

        return (new Extension(self::$url, $address, 'Address'))->toArray();
    }
}

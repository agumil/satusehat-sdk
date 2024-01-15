<?php
namespace agumil\SatuSehatSDK\DataType;

class AddressExtended
{
    private Address $address;

    private array $address_extensions;

    public function __construct(Address $address, ExtensionAdministrativeCode ...$addressExtensions)
    {
        $this->address = $address;
        $this->address_extensions = $addressExtensions;
    }

    public function toArray(): array
    {
        $data = $this->address->toArray();

        foreach ($this->address_extensions as $extension) {
            $data['extension'][] = $extension->toArray();
        }

        return $data;
    }
}

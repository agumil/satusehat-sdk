<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\AddressExtended;
use agumil\SatuSehatSDK\DataType\BundleContactPoint;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;

class PayloadBuilderOrganization
{
    private static $resource_type = 'Organization';

    private bool $active;

    private string $name;

    private array $alias;

    private array $identifier;

    private array $type;

    private array $telecom;

    private array $address;

    private array $part_of;

    private array $contact;

    private array $endpoint;

    public function setActive(bool $isActive)
    {
        $this->active = boolval($isActive);

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function addAlias(string $alias)
    {
        $this->alias[] = $alias;

        return $this;
    }

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function addType(CodeableConcept $type)
    {
        $this->type[] = $type->toArray();

        return $this;
    }

    public function addTelecom(ContactPoint $telecom)
    {
        $this->telecom[] = $telecom->toArray();

        return $this;
    }

    public function addAddress(Address $address, ExtensionAdministrativeCode ...$extensions)
    {
        $dataAddress = $address->toArray();

        foreach ($extensions as $extension) {
            $dataAddress['extension'][] = $extension->toArray();
        }

        $this->address[] = $dataAddress;

        return $this;
    }

    public function setPartOf(Reference $partOf)
    {
        $this->part_of = $partOf->toArray();

        return $this;
    }

    public function addContact(?CodeableConcept $purpose = null, ?HumanName $name = null, ?BundleContactPoint $telecom = null, AddressExtended ...$addressExtendeds)
    {
        if (isset($purpose)) {
            $data['purpose'] = $purpose->toArray();
        }

        if (isset($name)) {
            $data['name'] = $name->toArray();
        }

        if (isset($telecom)) {
            $data['telecom'] = $telecom->toArray();
        }

        foreach ($addressExtendeds as $addressExtended) {
            $data['address'][] = $addressExtended->toArray();
        }

        $this->contact[] = $data;

        return $this;
    }

    public function addEndpoint(Reference $endpoint)
    {
        $this->endpoint[] = $endpoint->toArray();

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->name)) {
            $data['name'] = $this->name;
        }

        if (!empty($this->alias)) {
            $data['alias'] = $this->alias;
        }

        if (!empty($this->active)) {
            $data['active'] = $this->active;
        }

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->telecom)) {
            $data['telecom'] = $this->telecom;
        }

        if (!empty($this->address)) {
            $data['address'] = $this->address;
        }

        if (!empty($this->part_of)) {
            $data['partOf'] = $this->part_of;
        }

        if (!empty($this->contact)) {
            $data['contact'] = $this->contact;
        }

        if (!empty($this->endpoint)) {
            $data['endpoint'] = $this->endpoint;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

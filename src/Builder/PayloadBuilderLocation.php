<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;

class PayloadBuilderLocation
{
    private static $resource_type = 'Location';

    private string $status;

    private string $name;

    private array $operational_status;

    private string $description;

    private string $mode;

    private array $type;

    private array $physical_type;

    private array $identifier;

    private array $telecom;

    private array $address;

    private array $position;

    private array $managing_organization;

    private array $part_of;

    private array $hours_of_operation;

    private string $availability_exception;

    private array $service_class;

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    public function setOperationalStatus(Coding $operationalStatus)
    {
        $this->operational_status = $operationalStatus->toArray();

        return $this;
    }

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function addPhysicalType(Coding $physicalType)
    {
        $this->physical_type[] = $physicalType->toArray();

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

    public function addAddress(Address $address, ExtensionAdministrativeCode...$extensions)
    {
        $dataAddress = $address->toArray();

        foreach ($extensions as $extension) {
            $dataAddress['extension'][] = $extension->toArray();
        }

        $this->address[] = $dataAddress;

        return $this;
    }

    public function setPosition(float $longitude, float $latitude, float $altitude)
    {
        $this->position['longitude'] = $longitude;
        $this->position['latitude'] = $latitude;
        $this->position['altitude'] = $altitude;

        return $this;
    }

    public function setManagingOrganization(Reference $managingOrganization)
    {
        $this->managing_organization = $managingOrganization->toArray();

        return $this;
    }

    public function setPartOf(Reference $partOf)
    {
        $this->part_of = $partOf->toArray();

        return $this;
    }

    public function addHoursOfOperation(array $daysOfWeek, bool $allDay, string $openingTime, string $closingTime)
    {
        $this->hours_of_operation[] = [
            'daysOfWeek' => $daysOfWeek,
            'allDay' => $allDay,
            'openingTime' => $openingTime,
            'closingTime' => $closingTime,
        ];

        return $this;
    }

    public function setAvailabilityExceptions(string $availabilityExceptions)
    {
        $this->availability_exception = $availabilityExceptions;

        return $this;
    }

    public function setServiceClass(CodeableConcept $serviceClass)
    {
        $this->service_class = $serviceClass->toArray();

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->status)) {
            $data['status'] = $this->status;
        }

        if (!empty($this->name)) {
            $data['name'] = $this->name;
        }

        if (!empty($this->description)) {
            $data['description'] = $this->description;
        }

        if (!empty($this->operational_status)) {
            $data['operationalStatus'] = $this->operational_status;
        }

        if (!empty($this->mode)) {
            $data['mode'] = $this->mode;
        }

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->physical_type)) {
            $data['physicalType'] = $this->physical_type;
        }

        if (!empty($this->telecom)) {
            $data['telecom'] = $this->telecom;
        }

        if (!empty($this->address)) {
            $data['address'] = $this->address;
        }

        if (!empty($this->position)) {
            $data['position'] = $this->position;
        }

        if (!empty($this->managing_organization)) {
            $data['managingOrganization'] = $this->managing_organization;
        }

        if (!empty($this->part_of)) {
            $data['partOf'] = $this->part_of;
        }

        if (!empty($this->hours_of_operation)) {
            $data['hoursOfOperation'] = $this->hours_of_operation;
        }

        if (!empty($this->availability_exception)) {
            $data['availabilityExceptions'] = $this->availability_exception;
        }

        if (!empty($this->service_class)) {
            $data['extension']['serviceClass'] = [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass',
                'extension' => $this->service_class,
            ];
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

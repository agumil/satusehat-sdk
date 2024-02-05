<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Period;
use agumil\SatuSehatSDK\DataType\Quantity;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class PayloadBuilderObservation
{
    private static $resource_type = 'Observation';

    private array $identifier;

    private string $status;

    private array $code;

    private array $subject;

    private array $encounter;

    private array $performer;

    private array $component;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function setCode(CodeableConcept $code)
    {
        $this->code = $code->toArray();

        return $this;
    }

    public function setSubject(Reference $subject)
    {
        $this->subject = $subject->toArray();

        return $this;
    }

    public function setEncounter(Reference $encounter)
    {
        $this->encounter = $encounter->toArray();

        return $this;
    }

    public function addPerformer(Reference $performer)
    {
        $this->performer[] = $performer->toArray();

        return $this;
    }

    public function addComponent(CodeableConcept $code, Quantity | CodeableConcept | Period | string | bool | int $value)
    {
        $data['code'] = $code->toArray();

        if ($value instanceof Quantity) {
            $data['valueQuantity'] = $value->toArray();
        } elseif ($value instanceof CodeableConcept) {
            $data['valueCodeableConcept'] = $value->toArray();
        } elseif ($value instanceof Period) {
            $data['valuePeriod'] = $value->toArray();
        } elseif (is_string($value)) {
            $data['valueString'] = $value;
        } elseif (is_int($value)) {
            $data['valueInteger'] = $value;
        } elseif (is_bool($value)) {
            $data['valueBoolean'] = $value;
        } else {
            throw new SSDataTypeException('Value data type is not supported');
        }

        $this->component[] = $data;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->status)) {
            $data['status'] = $this->status;
        }

        if (!empty($this->code)) {
            $data['code'] = $this->code;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->encounter)) {
            $data['encounter'] = $this->encounter;
        }

        if (!empty($this->performer)) {
            $data['performer'] = $this->performer;
        }

        if (!empty($this->component)) {
            $data['component'] = $this->component;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

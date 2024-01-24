<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\ExtensionTransportedTime;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;

class PayloadBuilderSpecimen
{
    private static $resource_type = 'Specimen';

    private array $identifier;

    private string $status;

    private array $type;

    private array $subject;

    private array $transport_time;

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

    public function setType(CodeableConcept $type)
    {
        $this->type = $type->toArray();

        return $this;
    }

    public function setSubject(Reference $subject)
    {
        $this->subject = $subject->toArray();

        return $this;
    }

    public function setTransportTime(ExtensionTransportedTime $transportTime)
    {
        $this->transport_time = $transportTime->toArray();

        return $this;
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

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->transport_time)) {
            $data['extension'][] = $this->transport_time;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

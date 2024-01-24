<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;

class PayloadBuilderServiceRequest
{
    private static $resource_type = 'ServiceRequest';

    private array $identifier;

    private string $status;

    private string $intent;

    private array $code;

    private array $subject;

    private array $encounter;

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

    public function setIntent(string $intent)
    {
        $this->intent = $intent;

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

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->status)) {
            $data['status'] = $this->status;
        }

        if (!empty($this->intent)) {
            $data['intent'] = $this->intent;
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

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

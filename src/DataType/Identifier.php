<?php
namespace agumil\SatuSehatSDK\DataType;

class Identifier
{
    private string $use;

    private string $system;

    private string $value;

    private ?CodeableConcept $type;

    private ?Period $period;

    private ?Reference $assigner;

    public function __construct(string $use, string $system, string $value, ?CodeableConcept $type = null, ?Period $period = null, ?Reference $assigner = null)
    {
        $this->use = $use;
        $this->system = $system;
        $this->value = $value;
        $this->type = $type;
        $this->period = $period;
        $this->assigner = $assigner;
    }

    public function toArray(): array
    {
        $data = [
            'use' => $this->use,
            'system' => $this->system,
            'value' => $this->value,
        ];

        if (isset($this->type)) {
            $data['type'] = $this->type->toArray();
        }

        if (isset($this->period)) {
            $data['period'] = $this->period->toArray();
        }

        if (isset($this->assigner)) {
            $data['assigner'] = $this->assigner->toArray();
        }

        return $data;
    }
}

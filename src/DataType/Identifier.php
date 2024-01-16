<?php
namespace agumil\SatuSehatSDK\DataType;

class Identifier
{
    private ?string $system;

    private ?string $use;

    private ?string $value;

    private ?CodeableConcept $type;

    private ?Period $period;

    private ?Reference $assigner;

    public function __construct(?string $system = null, ?string $use = null, ?string $value = null, ?CodeableConcept $type = null, ?Period $period = null, ?Reference $assigner = null)
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
        if (isset($this->system)) {
            $data['system'] = $this->system;
        }
        
        if (isset($this->use)) {
            $data['use'] = $this->use;
        }

        if (isset($this->value)) {
            $data['value'] = $this->value;
        }

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

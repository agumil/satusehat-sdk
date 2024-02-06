<?php
namespace agumil\SatuSehatSDK\DataType;

class SimpleQuantity extends Quantity
{
    public function __construct(?string $system = null, ?string $code = null, $value = null, ?string $unit = null)
    {
        parent::__construct($system, $code, $value, $unit);
    }

    public function toArray(): array
    {
        if (isset($this->system)) {
            $data['system'] = $this->system;
        }

        if (isset($this->code)) {
            $data['code'] = $this->code;
        }

        if (isset($this->value)) {
            $data['value'] = $this->value;
        }

        if (isset($this->unit)) {
            $data['unit'] = $this->unit;
        }

        return $data;
    }
}

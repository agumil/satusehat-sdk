<?php
namespace agumil\SatuSehatSDK\DataType;

class Duration extends Quantity
{
    public function __construct(?string $system = null, ?string $code = null, $value = null, ?string $unit = null)
    {
        $this->value = $value;
        $this->code = $code;
        $this->system = $system;
        $this->unit = $unit;
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

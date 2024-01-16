<?php
namespace agumil\SatuSehatSDK\DataType;

class Quantity
{
    protected $value;

    protected ?string $code;

    protected ?string $system;

    protected ?string $unit;

    protected ?string $comparator;

    public function __construct(?string $system = null, ?string $code = null, $value = null, ?string $unit = null, ?string $comparator = null)
    {
        $this->value = $value;
        $this->code = $code;
        $this->system = $system;
        $this->unit = $unit;
        $this->comparator = $comparator;
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

        if (isset($this->comparator)) {
            $data['comparator'] = $this->comparator;
        }

        return $data;
    }
}

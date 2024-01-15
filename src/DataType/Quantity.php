<?php
namespace agumil\SatuSehatSDK\DataType;

class Quantity
{
    protected float|int $value;

    protected string $code;

    protected ?string $system;

    protected ?string $unit;

    protected ?string $comparator;

    public function __construct(float | int $value, string $code, ?string $system = null, ?string $unit = null, ?string $comparator = null)
    {
        $this->value = $value;
        $this->code = $code;
        $this->system = $system;
        $this->unit = $unit;
        $this->comparator = $comparator;
    }

    public function toArray(): array
    {
        $data = [
            'value' => $this->value,
            'code' => $this->code,
        ];

        if (!empty($this->system)) {
            $data['system'] = $this->system;
        }

        if (!empty($this->unit)) {
            $data['unit'] = $this->unit;
        }

        if (!empty($this->comparator)) {
            $data['comparator'] = $this->comparator;
        }

        return $data;
    }
}

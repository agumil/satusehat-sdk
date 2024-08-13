<?php
namespace agumil\SatuSehatSDK\DataType;

class Count
{
    private Quantity $quantity;

    public function __construct(?string $system = null, ?string $code = null, $value = null, ?string $unit = null)
    {
        $this->quantity = new Quantity($system, $code, $value, $unit, '=');
    }

    public function toArray(): array
    {
        return $this->quantity->toArray();
    }
}

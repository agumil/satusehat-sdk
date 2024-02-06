<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class Age extends Quantity
{
    public function __construct(int $age, ?string $comparator = null)
    {
        if ($age < 0) {
            throw new SSDataTypeException('Age value must be greater than or equal to zero');
        }

        parent::__construct('http://unitsofmeasure.org', 'a', $age, 'year', $comparator);
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

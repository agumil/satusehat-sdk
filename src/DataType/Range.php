<?php
namespace agumil\SatuSehatSDK\DataType;

class Range
{
    protected SimpleQuantity $low;

    protected SimpleQuantity $high;

    public function __construct($low, $high, $unit_code)
    {
        $this->low = new SimpleQuantity('http://unitsofmeasure.org', $unit_code, $low);
        $this->high = new SimpleQuantity('http://unitsofmeasure.org', $unit_code, $high);
    }

    public function toArray(): array
    {
        return [
            'low' => $this->low->toArray(),
            'high' => $this->high->toArray(),
        ];
    }
}

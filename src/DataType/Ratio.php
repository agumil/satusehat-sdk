<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class Ratio
{
    protected SimpleQuantity|int $numerator;

    protected SimpleQuantity|int $denominator;

    public function __construct(SimpleQuantity | int $numerator = null, SimpleQuantity | int $denominator = null)
    {
        $bothEmpty = isset($numerator) && isset($denominator);
        $bothExist = !isset($numerator) && !isset($denominator);
        if (!$bothEmpty && !$bothExist) {
            throw new SSDataTypeException('Numerator and denominator SHALL both be present, or both are absent. If both are absent, there SHALL be some extension present');
        }

        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }

    public function toArray(): array
    {
        $numerator = [];
        $denominator = [];

        if ($this->numerator instanceof SimpleQuantity) {
            $numerator = $this->numerator->toArray();
        } else {
            $numerator = ['value' => (string) $this->numerator];
        }

        if ($this->denominator instanceof SimpleQuantity) {
            $denominator = $this->denominator->toArray();
        } else {
            $denominator = ['value' => (string) $this->denominator];
        }

        return [
            'numerator' => $numerator,
            'denominator' => $denominator,
        ];
    }
}

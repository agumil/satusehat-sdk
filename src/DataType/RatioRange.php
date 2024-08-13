<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class RatioRange
{
    protected SimpleQuantity|int $lowNumerator;

    protected SimpleQuantity|int $highNumerator;

    protected SimpleQuantity|int $denominator;

    public function __construct(SimpleQuantity | int $lowNumerator = null, SimpleQuantity | int $highNumerator = null, SimpleQuantity | int $denominator = null)
    {
        $numeratorExist = isset($lowNumerator) || isset($highNumerator);
        $denominatorExist = isset($denominator);
        if (!$numeratorExist && $denominatorExist) {
            throw new SSDataTypeException('One of lowNumerator or highNumerator and denominator SHALL be present, or all are absent. If all are absent, there SHALL be some extension present');
        }

        $this->lowNumerator = $lowNumerator;
        $this->highNumerator = $highNumerator;
        $this->denominator = $denominator;
    }

    public function toArray(): array
    {
        $lowNumerator = [];
        $highNumerator = [];
        $denominator = [];

        if ($this->lowNumerator instanceof SimpleQuantity) {
            $lowNumerator = $this->lowNumerator->toArray();
        } else {
            $lowNumerator = ['value' => (string) $this->lowNumerator];
        }

        if ($this->highNumerator instanceof SimpleQuantity) {
            $highNumerator = $this->highNumerator->toArray();
        } else {
            $highNumerator = ['value' => (string) $this->highNumerator];
        }

        if ($this->denominator instanceof SimpleQuantity) {
            $denominator = $this->denominator->toArray();
        } else {
            $denominator = ['value' => (string) $this->denominator];
        }

        return [
            'lowNumerator' => $lowNumerator,
            'highNumerator' => $highNumerator,
            'denominator' => $denominator,
        ];
    }
}

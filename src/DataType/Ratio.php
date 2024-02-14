<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class Ratio
{
    protected Quantity|int $numerator;

    protected Quantity|int $denominator;

    public function __construct(Quantity | int $numerator = null, Quantity | int $denominator = null)
    {
        $bothEmpty = empty($numerator) && empty($denominator);
        $bothExist = !empty($numerator) && !empty($denominator);
        if (!$bothEmpty && !$bothExist) {
            throw new SSDataTypeException('Both numerator and denominator must be empty OR must be exist');
        }

        $numeratorDataType = gettype($numerator);
        $denominatorDataType = gettype($denominator);

        if ($numeratorDataType !== $denominatorDataType) {
            throw new SSDataTypeException('Both numerator and denominator data type must be the same');
        }

        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }

    public function toArray(): array
    {
        $numerator = [];
        $denominator = [];
        if ($this->numerator instanceof Quantity) {
            $numerator = $this->numerator->toArray();
            $denominator = $this->denominator->toArray();
        }

        if (is_integer($this->numerator)) {
            $numerator = ['value' => (string) $this->numerator];
            $denominator = ['value' => (string) $this->denominator];
        }

        return [
            'numerator' => $numerator,
            'denominator' => $denominator,
        ];
    }
}

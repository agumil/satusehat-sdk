<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\HL7\QuantityComparator;

class Quantity
{
    protected $value;

    protected ?string $code;

    protected ?string $system;

    protected ?string $unit;

    protected ?string $comparator;

    public function __construct(?string $system = null, ?string $code = null, $value = null, ?string $unit = null, ?string $comparator = null)
    {
        if (!empty($comparator)) {
            $allowed_comparator = QuantityComparator::getCodes();
            if (!in_array($comparator, $allowed_comparator)) {
                $allowed_comparator = implode(',', $allowed_comparator);
                throw new SSDataTypeException("comparator must be one of {$allowed_comparator}");
            }

            $this->comparator = $comparator;
        }

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

        if (isset($this->comparator)) {
            $data['comparator'] = $this->comparator;
        }

        return $data;
    }
}

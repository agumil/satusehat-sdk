<?php
namespace agumil\SatuSehatSDK\DataType;

class CodeableReference
{
    private ?CodeableConcept $codeableConcept;

    private ?Reference $reference;

    public function __construct(?CodeableConcept $codeableConcept = null, ?Reference $reference = null)
    {
        $this->codeableConcept = $codeableConcept;
        $this->reference = $reference;
    }

    public function toArray(): array
    {
        if (isset($this->codeableConcept)) {
            $data['concept'] = $this->codeableConcept->toArray();
        }

        if (isset($this->reference)) {
            $data['reference'] = $this->reference->toArray();
        }

        return $data;
    }
}

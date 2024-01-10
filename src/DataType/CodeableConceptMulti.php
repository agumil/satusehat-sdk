<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\DataType\CodeableConcept;

class CodeableConceptMulti
{
    private array $codeable_concepts;

    public function __construct(CodeableConcept ...$codeableConcept)
    {
        $this->codeable_concepts = $codeableConcept;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->codeable_concepts as $codeableConcept) {
            $data[] = $codeableConcept->toArray();
        }

        return $data;
    }
}

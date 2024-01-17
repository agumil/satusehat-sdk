<?php
namespace agumil\SatuSehatSDK\DataType;

class BundleCodeableConcept
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

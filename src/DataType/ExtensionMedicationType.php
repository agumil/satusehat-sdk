<?php
namespace agumil\SatuSehatSDK\DataType;

class ExtensionMedicationType
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType';

    private CodeableConcept $value;

    public function __construct(CodeableConcept $value)
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        $extension = new Extension(self::$url, $this->value->toArray(), 'CodeableConcept');

        return $extension->toArray();
    }
}

<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\KemKes\MedicationType;

class ExtensionMedicationType
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType';

    private CodeableConcept $value;

    public function __construct(string $medicationType)
    {
        ValidatorHelper::in('medicationType', $medicationType, MedicationType::getCodes());

        $system = MedicationType::SYSTEM;
        $display = MedicationType::getDisplayCode($medicationType);

        $this->value = (new CodeableConcept($display, new Coding($system, $medicationType, $display)));
    }

    public function toArray(): array
    {
        $extension = new Extension(self::$url, $this->value->toArray(), 'CodeableConcept');

        return $extension->toArray();
    }
}

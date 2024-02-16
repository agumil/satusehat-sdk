<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassInpatient;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;

class ExtensionLocationServiceClass
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass';

    private ?CodeableConcept $value;

    public function __construct(string $class)
    {
        $inpatient = ServiceClassInpatient::getCodes();
        $outpatient = ServiceClassOutpatient::getCodes();
        $availableClasses = array_merge($inpatient, $outpatient);

        ValidatorHelper::in('class', $class, $availableClasses);

        if (in_array($class, $inpatient)) {
            $system1 = ServiceClassInpatient::SYSTEM;
            $display1 = ServiceClassInpatient::getDisplayCode($class);
        } else {
            $system1 = ServiceClassOutpatient::SYSTEM;
            $display1 = ServiceClassOutpatient::getDisplayCode($class);
        }

        $this->value = new CodeableConcept($display1, new Coding($system1, $class, $display1));
    }

    public function toArray(): array
    {
        return (new Extension(self::$url, $this->value->toArray(), 'CodeableConcept'))->toArray();
    }
}

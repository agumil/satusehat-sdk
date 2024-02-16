<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassInpatient;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassUpgrade;

class ExtensionServiceClass
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass';

    private ?CodeableConcept $value;

    private ?CodeableConcept $upgrade_class_indicator;

    public function __construct(string $class, ?string $upgradeClass = null)
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

        if (isset($upgradeClass)) {
            ValidatorHelper::in('upgradeClass', $upgradeClass, ServiceClassUpgrade::getCodes());

            $system2 = ServiceClassUpgrade::SYSTEM;
            $display2 = ServiceClassUpgrade::getDisplayCode($upgradeClass);

            $this->upgrade_class_indicator = new CodeableConcept($display2, new Coding($system2, $upgradeClass, $display2));
        }
    }

    public function toArray(): array
    {
        $extensions = [];

        if (isset($this->value)) {
            $extensions[] = new Extension('value', $this->value->toArray(), 'CodeableConcept');
        }

        if (isset($this->upgrade_class_indicator)) {
            $extensions[] = new Extension('upgradeClassIndicator', $this->upgrade_class_indicator->toArray(), 'CodeableConcept');
        }

        return (new ExtensionExtended(self::$url, ...$extensions))->toArray();
    }
}

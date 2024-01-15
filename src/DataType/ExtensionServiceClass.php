<?php
namespace agumil\SatuSehatSDK\DataType;

class ExtensionServiceClass
{
    private ?CodeableConcept $value;

    private ?CodeableConcept $upgrade_class_indicator;

    public function __construct(?CodeableConcept $value = null, ?CodeableConcept $upgradeClassIndicator = null)
    {
        $this->value = $value;
        $this->upgrade_class_indicator = $upgradeClassIndicator;
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

        $extensionExtended = new ExtensionExtended(
            'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass',
            ...$extensions
        );

        return $extensionExtended->toArray();
    }
}

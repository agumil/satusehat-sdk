<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class MedicationRequestIntent implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/CodeSystem/medicationrequest-intent';

    const CODE_PROPOSAL = 'proposal';
    const CODE_PLAN = 'plan';
    const CODE_ORDER = 'order';
    const CODE_ORIGINAL_ORDER = 'original-order';
    const CODE_REFLEX_ORDER = 'reflex-order';
    const CODE_FILLER_ORDER = 'filler-order';
    const CODE_INSTANCE_ORDER = 'instance-order';
    const CODE_OPTION = 'option';

    public static function getCodes(): array
    {
        return [
            self::CODE_PROPOSAL,
            self::CODE_PLAN,
            self::CODE_ORDER,
            self::CODE_ORIGINAL_ORDER,
            self::CODE_REFLEX_ORDER,
            self::CODE_FILLER_ORDER,
            self::CODE_INSTANCE_ORDER,
            self::CODE_OPTION,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PROPOSAL => 'Proposal',
            self::CODE_PLAN => 'Plan',
            self::CODE_ORDER => 'Order',
            self::CODE_ORIGINAL_ORDER => 'Original Order',
            self::CODE_REFLEX_ORDER => 'Reflex Order',
            self::CODE_FILLER_ORDER => 'Filler Order',
            self::CODE_INSTANCE_ORDER => 'Instance Order',
            self::CODE_OPTION => 'Option',
        ];

        return @$displays[$code];
    }
}

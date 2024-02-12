<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class RequestIntent implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/request-intent';

    const CODE_PROPOSAL = 'proposal';
    const CODE_PLAN = 'plan';
    const CODE_DIRECTIVE = 'directive';
    const CODE_ORDER = 'order';
    const CODE_ORDER_ORIGINAL = 'original-order';
    const CODE_ORDER_REFLEX = 'reflex-order';
    const CODE_ORDER_FILLER = 'filler-order';
    const CODE_ORDER_INSTANCE = 'instance-order';
    const CODE_OPTION = 'option';

    public static function getCodes(): array
    {
        return [
            self::CODE_PROPOSAL,
            self::CODE_PLAN,
            self::CODE_DIRECTIVE,
            self::CODE_ORDER,
            self::CODE_ORDER_ORIGINAL,
            self::CODE_ORDER_REFLEX,
            self::CODE_ORDER_FILLER,
            self::CODE_ORDER_INSTANCE,
            self::CODE_OPTION,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PROPOSAL => 'Proposal',
            self::CODE_PLAN => 'Plan',
            self::CODE_DIRECTIVE => 'Directive',
            self::CODE_ORDER => 'Order',
            self::CODE_ORDER_ORIGINAL => 'Original Order',
            self::CODE_ORDER_REFLEX => 'Reflex Order',
            self::CODE_ORDER_FILLER => 'Filler Order',
            self::CODE_ORDER_INSTANCE => 'Instance Order',
            self::CODE_OPTION => 'Option',
        ];

        return @$displays[$code];
    }
}

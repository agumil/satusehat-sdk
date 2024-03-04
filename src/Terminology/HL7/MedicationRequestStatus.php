<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class MedicationRequestStatus implements TerminologyInterface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/CodeSystem/medicationrequest-status';

    const CODE_ACTIVE = 'active';
    const CODE_ON_HOLD = 'on-hold';
    const CODE_ENDED = 'ended';
    const CODE_STOPPED = 'stopped';
    const CODE_COMPLETED = 'completed';
    const CODE_CANCELLED = 'cancelled';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';
    const CODE_DRAFT = 'draft';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_ACTIVE,
            self::CODE_ON_HOLD,
            self::CODE_ENDED,
            self::CODE_STOPPED,
            self::CODE_COMPLETED,
            self::CODE_CANCELLED,
            self::CODE_ENTERED_IN_ERROR,
            self::CODE_DRAFT,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_ACTIVE => 'Active',
            self::CODE_ON_HOLD => 'On Hold',
            self::CODE_ENDED => 'Ended',
            self::CODE_STOPPED => 'Stopped',
            self::CODE_COMPLETED => 'Completed',
            self::CODE_CANCELLED => 'Cancelled',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
            self::CODE_DRAFT => 'Draft',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

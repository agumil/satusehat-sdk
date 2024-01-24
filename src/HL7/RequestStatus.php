<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class RequestStatus implements HL7Interface
{
    const VERSION = '4.0.1';
    const SYSTEM = 'http://hl7.org/fhir/request-status';

    const CODE_DRAFT = 'draft';
    const CODE_ACTIVE = 'active';
    const CODE_ON_HOLD = 'on-hold';
    const CODE_REVOKED = 'revoked';
    const CODE_COMPLETED = 'completed';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_DRAFT,
            self::CODE_ACTIVE,
            self::CODE_ON_HOLD,
            self::CODE_REVOKED,
            self::CODE_COMPLETED,
            self::CODE_ENTERED_IN_ERROR,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_DRAFT => 'Draft',
            self::CODE_ACTIVE => 'Active',
            self::CODE_ON_HOLD => 'On Hold',
            self::CODE_REVOKED => 'Revoked',
            self::CODE_COMPLETED => 'Completed',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

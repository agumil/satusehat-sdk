<?php
namespace agumil\SatuSehatSDK\Terminology\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class DiagnosticReportStatus implements TerminologyInterface
{
    const VERSION = '4.0.1';
    const SYSTEM = 'http://hl7.org/fhir/diagnostic-report-status';

    const CODE_REGISTERED = 'registered';
    const CODE_PARTIAL = 'partial';
    const CODE_PRELIMINARY = 'preliminary';
    const CODE_FINAL = 'final';
    const CODE_AMENDED = 'amended';
    const CODE_CORRECTED = 'corrected';
    const CODE_APPENDED = 'appended';
    const CODE_CANCELLED = 'cancelled';
    const CODE_ENTERED_IN_ERROR = 'entered-in-error';
    const CODE_UNKNOWN = 'unknown';

    public static function getCodes(): array
    {
        return [
            self::CODE_REGISTERED,
            self::CODE_PARTIAL,
            self::CODE_PRELIMINARY,
            self::CODE_FINAL,
            self::CODE_AMENDED,
            self::CODE_CORRECTED,
            self::CODE_APPENDED,
            self::CODE_CANCELLED,
            self::CODE_ENTERED_IN_ERROR,
            self::CODE_UNKNOWN,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_REGISTERED => 'Registered',
            self::CODE_PARTIAL => 'Partial',
            self::CODE_PRELIMINARY => 'Preliminary',
            self::CODE_FINAL => 'Final',
            self::CODE_AMENDED => 'Amended',
            self::CODE_CORRECTED => 'Corrected',
            self::CODE_APPENDED => 'Appended',
            self::CODE_CANCELLED => 'Cancelled',
            self::CODE_ENTERED_IN_ERROR => 'Entered in Error',
            self::CODE_UNKNOWN => 'Unknown',
        ];

        return @$displays[$code];
    }
}

<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class ContactEntityType implements TerminologyInterface
{
    const VERSION = '1.0.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/contactentity-type';

    const CODE_BILLING = 'BILL';
    const CODE_ADMINISTRATIVE = 'ADMIN';
    const CODE_HUMAN_RESOURCE = 'HR';
    const CODE_PAYOR = 'PAYOR';
    const CODE_PATIENT = 'PATINF';
    const CODE_PRESS = 'PRESS';

    public static function getCodes(): array
    {
        return [
            self::CODE_BILLING,
            self::CODE_ADMINISTRATIVE,
            self::CODE_HUMAN_RESOURCE,
            self::CODE_PAYOR,
            self::CODE_PATIENT,
            self::CODE_PRESS,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_BILLING => 'Billing',
            self::CODE_ADMINISTRATIVE => 'Administrative',
            self::CODE_HUMAN_RESOURCE => 'Human Resource',
            self::CODE_PAYOR => 'Payor',
            self::CODE_PATIENT => 'Patient',
            self::CODE_PRESS => 'Press',
        ];

        return @$displays[$code];
    }
}

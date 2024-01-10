<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\HL7Interface;

class ContactPointSystem implements HL7Interface
{
    const VERSION = '5.0.0';
    const SYSTEM = 'http://hl7.org/fhir/ValueSet/contact-point-system';

    const CODE_PHONE = 'phone';
    const CODE_FAX = 'fax';
    const CODE_EMAIL = 'email';
    const CODE_PAGER = 'pager';
    const CODE_URL = 'url';
    const CODE_SMS = 'sms';
    const CODE_OTHER = 'other';

    public static function getCodes(): array
    {
        return [
            self::CODE_PHONE,
            self::CODE_FAX,
            self::CODE_EMAIL,
            self::CODE_PAGER,
            self::CODE_URL,
            self::CODE_SMS,
            self::CODE_OTHER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_PHONE => 'Phone',
            self::CODE_FAX => 'Fax',
            self::CODE_EMAIL => 'Email',
            self::CODE_PAGER => 'Pager',
            self::CODE_URL => 'URL',
            self::CODE_SMS => 'SMS',
            self::CODE_OTHER => 'Other',
        ];

        return @$displays[$code];
    }
}

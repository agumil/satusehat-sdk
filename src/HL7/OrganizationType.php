<?php
namespace agumil\SatuSehatSDK\HL7;

use agumil\SatuSehatSDK\Interface\TerminologyInterface;

class OrganizationType implements TerminologyInterface
{
    const VERSION = '5.4.0';
    const SYSTEM = 'http://terminology.hl7.org/CodeSystem/organization-type';

    const CODE_HEALTHCARE_PROVIDER = 'prov';
    const CODE_HOSPITAL_DEPARTMENT = 'dept';
    const CODE_ORGANIZATIONAL_TEAM = 'team';
    const CODE_GOVERNMENT = 'govt';
    const CODE_INSURANCE_COMPANY = 'ins';
    const CODE_PAYER = 'pay';
    const CODE_EDUCATIONAL_INSTITUTE = 'edu';
    const CODE_RELIGIOUS_INSTITUTION = 'reli';
    const CODE_CLINICAL_RESEARCH_SPONSOR = 'crs';
    const CODE_COMMUNITY_GROUP = 'cg';
    const CODE_NON_HEALTHCARE_BUSINESS_OR_CORPORATION = 'bus';
    const CODE_OTHER = 'other';

    public static function getCodes(): array
    {
        return [
            self::CODE_HEALTHCARE_PROVIDER,
            self::CODE_HOSPITAL_DEPARTMENT,
            self::CODE_ORGANIZATIONAL_TEAM,
            self::CODE_GOVERNMENT,
            self::CODE_INSURANCE_COMPANY,
            self::CODE_PAYER,
            self::CODE_EDUCATIONAL_INSTITUTE,
            self::CODE_RELIGIOUS_INSTITUTION,
            self::CODE_CLINICAL_RESEARCH_SPONSOR,
            self::CODE_COMMUNITY_GROUP,
            self::CODE_NON_HEALTHCARE_BUSINESS_OR_CORPORATION,
            self::CODE_OTHER,
        ];
    }

    public static function getDisplayCode(string $code): null | string
    {
        $displays = [
            self::CODE_HEALTHCARE_PROVIDER => 'Healthcare Provider',
            self::CODE_HOSPITAL_DEPARTMENT => 'Hospital Department',
            self::CODE_ORGANIZATIONAL_TEAM => 'Organizational team',
            self::CODE_GOVERNMENT => 'Government',
            self::CODE_INSURANCE_COMPANY => 'Insurance Company',
            self::CODE_PAYER => 'Payer',
            self::CODE_EDUCATIONAL_INSTITUTE => 'Educational Institute',
            self::CODE_RELIGIOUS_INSTITUTION => 'Religious Institution',
            self::CODE_CLINICAL_RESEARCH_SPONSOR => 'Clinical Research Sponsor',
            self::CODE_COMMUNITY_GROUP => 'Community Group',
            self::CODE_NON_HEALTHCARE_BUSINESS_OR_CORPORATION => 'Non-Healthcare Business or Corporation',
            self::CODE_OTHER => 'Other',
        ];

        return @$displays[$code];
    }
}

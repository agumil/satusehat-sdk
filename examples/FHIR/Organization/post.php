<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\HL7\AddressType;
use agumil\SatuSehatSDK\HL7\AddressUse;
use agumil\SatuSehatSDK\HL7\ContactEntityType;
use agumil\SatuSehatSDK\HL7\NameUse;
use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['base_url' => Endpoint::DEV_FHIR]);

// organization data
$org_name = 'Demo Organization';
$org_is_active = true;
$org_address = new Address(
    AddressUse::CODE_WORK,
    AddressType::CODE_BOTH,
    ['Jl. Contoh'],
    'Umbulharjo',
    'Yogyakarta Kota',
    'DIY',
    55162,
    'ID'
);

$contact_purpose_coding = new Coding(
    ContactEntityType::SYSTEM,
    ContactEntityType::CODE_PATIENT,
    ContactEntityType::getDisplayCode(ContactEntityType::CODE_PATIENT),
);
$contact_purpose = new CodeableConcept('', $contact_purpose_coding);

$contact_name = new HumanName(
    NameUse::CODE_OFFICIAL,
    ['Agung'],
    'Gumilang'
);

// init payload builder organization
$builder = new PayloadBuilderOrganization();

$payload = $builder->setName($org_name)
    ->setActive($org_is_active)
    ->addAddress($org_address)
    ->addContact($contact_purpose, $contact_name)
    ->build();

$response = $ssclient->createOrganization($payload);

var_dump($response->getContentAsObject());
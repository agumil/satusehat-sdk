<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\SSClient;
use agumil\SatuSehatSDK\Terminology\HL7\AddressType;
use agumil\SatuSehatSDK\Terminology\HL7\AddressUse;
use agumil\SatuSehatSDK\Terminology\HL7\ContactEntityType;
use agumil\SatuSehatSDK\Terminology\HL7\IdentifierUse;
use agumil\SatuSehatSDK\Terminology\HL7\NameUse;
use agumil\SatuSehatSDK\Terminology\KemKes\IdentifierSystem;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'development']);

// organization data
$org_identifier = new Identifier(
    IdentifierSystem::organization('84c403fb-d228-4b21-9557-0c58c618b8b9'),
    IdentifierUse::CODE_OFFICIAL,
    '1'
);
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

$part_of = new Reference('Organization/84c403fb-d228-4b21-9557-0c58c618b8b9');

// init payload builder organization
$builder = new PayloadBuilderOrganization();

$payload = $builder
    ->addIdentifier($org_identifier)
    ->setName($org_name)
    ->setActive($org_is_active)
    ->addAddress($org_address)
    ->addContact($contact_purpose, $contact_name)
    ->setPartOf($part_of)
    ->build();

$response = $ssclient->createOrganization($payload);

var_dump($response->getContentAsObject());

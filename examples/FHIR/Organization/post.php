<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\BundleContactPoint;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\SSClient;
use agumil\SatuSehatSDK\Terminology\HL7\AddressType;
use agumil\SatuSehatSDK\Terminology\HL7\AddressUse;
use agumil\SatuSehatSDK\Terminology\HL7\ContactEntityType;
use agumil\SatuSehatSDK\Terminology\HL7\ContactPointSystem;
use agumil\SatuSehatSDK\Terminology\HL7\ContactPointUse;
use agumil\SatuSehatSDK\Terminology\HL7\IdentifierUse;
use agumil\SatuSehatSDK\Terminology\HL7\NameUse;
use agumil\SatuSehatSDK\Terminology\KemKes\IdentifierSystem;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'development']);
$builder = new PayloadBuilderOrganization();

// add identifier
$identifier = new Identifier(
    IdentifierSystem::organization($parentOrganizationId),
    IdentifierUse::CODE_OFFICIAL,
    StrHelper::random(12, 'numeric')
);
$builder->addIdentifier($identifier);

// set name
$name = strtoupper(StrHelper::random(6, 'alpha')) . '-Demo Organization';
$builder->setName($name);

// set active
$is_active = true;
$builder->setActive($is_active);

// add address
$address = new Address(
    AddressUse::CODE_WORK,
    AddressType::CODE_BOTH,
    ['Jl. Contoh'],
    'Umbulharjo',
    'Yogyakarta Kota',
    'DIY',
    55162,
    'ID'
);
$builder->addAddress($address);

// add contact
$purpose = ContactEntityType::CODE_BILLING;
$name = new HumanName(NameUse::CODE_OFFICIAL, ['John', 'Doe']);
$email = new ContactPoint(ContactPointSystem::CODE_EMAIL, ContactPointUse::CODE_WORK, 'johndoe@example.com');
$phone = new ContactPoint(ContactPointSystem::CODE_PHONE, ContactPointUse::CODE_WORK, '+6285712345678');
$telecom = new BundleContactPoint($email, $phone);
$builder->addContact($purpose, $name, $telecom);

// set part of
// $part_of = new Reference('Organization/f78187ec-ef23-4a17-ae21-b1813a6be821');
// $builder->setPartOf($part_of);

// send request
$response = $ssclient->createOrganization($builder->build());

// get result as object
var_dump($response->getContentAsObject());

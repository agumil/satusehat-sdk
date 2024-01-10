<?php

use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\CodingMulti;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\Extension;
use agumil\SatuSehatSDK\DataType\ExtensionExtended;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\HL7\AddressType;
use agumil\SatuSehatSDK\HL7\AddressUse;
use agumil\SatuSehatSDK\HL7\ContactPointSystem;
use agumil\SatuSehatSDK\HL7\ContactPointUse;
use agumil\SatuSehatSDK\HL7\IdentifierUse;
use agumil\SatuSehatSDK\HL7\OrganizationType;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$address = new Address(
    AddressUse::CODE_HOME,
    AddressType::CODE_BOTH,
    ['Jl. Rajawali'],
    'Yogyakarta Kota',
    'Sleman',
    'DIY',
    '55162',
    'ID'
);

$url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode';
$addressExtensions = [
    new Extension('province', '31', 'Code'),
    new Extension('city', '3101', 'Code'),
    new Extension('district', '310101', 'Code'),
    new Extension('village', '31010101', 'Code'),
];

$addressExtension = new ExtensionExtended($url, ...$addressExtensions);

$identifier = new Identifier(
    IdentifierUse::CODE_OFFICIAL,
    'test',
    'test1'
);

$phone = new ContactPoint(
    ContactPointUse::CODE_MOBILE,
    ContactPointSystem::CODE_PHONE,
    '081215743120',
);

$email = new ContactPoint(
    ContactPointUse::CODE_HOME,
    ContactPointSystem::CODE_EMAIL,
    'agung96gumilang@gmail.com',
);

$type = new CodeableConcept(new CodingMulti(
    new Coding(
        OrganizationType::SYSTEM,
        OrganizationType::CODE_GOVT,
        OrganizationType::getDisplayCode(OrganizationType::CODE_GOVT)),
    new Coding(
        OrganizationType::SYSTEM,
        OrganizationType::CODE_DEPT,
        OrganizationType::getDisplayCode(OrganizationType::CODE_DEPT))
));

$builder = new PayloadBuilderOrganization();
$builder = $builder->setActive(true)
    ->setName('Test')
    ->addAddress($address, $addressExtension)
    ->addIdentifier($identifier)
    ->addTelecom($phone)
    ->addTelecom($email)
    ->addType($type)
    ->buildAsJSON();

var_dump($builder);

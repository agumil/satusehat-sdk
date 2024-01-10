<?php require __DIR__ . '/../../bootstrap.php';

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\CodingMulti;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\ContactPointMulti;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\HL7\AddressType;
use agumil\SatuSehatSDK\HL7\AddressUse;
use agumil\SatuSehatSDK\HL7\ContactEntityType;
use agumil\SatuSehatSDK\HL7\ContactPointSystem;
use agumil\SatuSehatSDK\HL7\ContactPointUse;
use agumil\SatuSehatSDK\HL7\NameUse;
use agumil\SatuSehatSDK\HL7\OrganizationType;
use agumil\SatuSehatSDK\SSClient;

$id = 'b152874d-20e1-424a-8c3f-e54c6fe09b94';
$builder = new PayloadBuilderOrganization();

$name_organization = 'Tes Create Organization RW136';
$builder->setName($name_organization);

// $identifier = new Identifier(
//     IdentifierUse::CODE_OFFICIAL,
//     'http://sys-ids.kemkes.go.id/organization/1000079374',
//     'R001',
// );
// $builder->addIdentifier($identifier);

$type_coding1 = new Coding(
    OrganizationType::SYSTEM,
    OrganizationType::CODE_HEALTHCARE_PROVIDER,
    OrganizationType::getDisplayCode(OrganizationType::CODE_HEALTHCARE_PROVIDER)
);
$type = new CodeableConcept(new CodingMulti($type_coding1));
$builder->addType($type);

$contact1 = new ContactPoint(
    ContactPointUse::CODE_WORK,
    ContactPointSystem::CODE_EMAIL,
    'john45doe@oneway.com'
);
$builder->addTelecom($contact1);

$contact2 = new ContactPoint(
    ContactPointUse::CODE_WORK,
    ContactPointSystem::CODE_PHONE,
    '+6281212345678'
);
$builder->addTelecom($contact2);

$address1 = new Address(
    AddressUse::CODE_TEMP,
    AddressType::CODE_BOTH,
    ['Jl. Rajawali No 17'],
    'Umbulharjo',
    'Yogyakarta Kota',
    'DIY',
    '55162',
    'ID'
);
$address1_extension = ss_address_extension(99, 9901);
$builder->addAddress($address1, $address1_extension);

$contact_purpose_coding1 = new Coding(
    ContactEntityType::SYSTEM,
    ContactEntityType::CODE_BILLING,
    ContactEntityType::getDisplayCode(ContactEntityType::CODE_BILLING)
);

$contact_purpose = new CodeableConcept(
    new CodingMulti($contact_purpose_coding1)
);

$contact_name = new HumanName(
    NameUse::CODE_OFFICIAL,
    ['Agung', 'Nama Tengah'],
    'Gumilang'
);

$contact_telecom1 = new ContactPoint(
    ContactPointUse::CODE_WORK,
    ContactPointSystem::CODE_PHONE,
    '+6281234567891'
);
$builder->addContact($contact_purpose, $contact_name, new ContactPointMulti($contact_telecom1));

$payload = $builder->build();

$config1['base_url'] = Endpoint::DEV_OAUTH2;
$config1['client_id'] = 'your_client_id';
$config1['client_secret'] = 'your_client_secret';

$config2['base_url'] = Endpoint::DEV_FHIR;

$ssclient = new SSClient(new Oauth2($config1), $config2);
$response = $ssclient->updateOrganization($id, $payload);

var_dump($response->getContentAsObject());

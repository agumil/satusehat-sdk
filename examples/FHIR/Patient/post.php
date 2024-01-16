<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderPatient;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\HL7\AdministrativeGender;
use agumil\SatuSehatSDK\HL7\IdentifierUse;
use agumil\SatuSehatSDK\HL7\NameUse;
use agumil\SatuSehatSDK\SSClient;

// init client
$ssclient = new SSClient($oauth2, ['base_url' => Endpoint::DEV_FHIR]);

// patient data
$identifier = new Identifier(
    'https://fhir.kemkes.go.id/id/nik',
    IdentifierUse::CODE_OFFICIAL,
    '123456789123456'
);
$name = new HumanName(
    NameUse::CODE_OFFICIAL,
    ['John', 'Middle'],
    'Doe'
);
$is_active = true;
$gender = AdministrativeGender::CODE_MALE;
$birth_date = '1980-01-01'; /// Y-m-d
$is_multiple_birth = false;
$multiple_birth = 0;
$language1_coding = new Coding(
    'urn:ietf:bcp:47',
    'id-ID',
    'Indonesian'
);
$language = new CodeableConcept('Indonesian', $language1_coding);

// init payload builder organization
$builder = new PayloadBuilderPatient();
$payload = $builder->addIdentifier($identifier)
    ->addName($name)
    ->setActive($is_active)
    ->setGender($gender)
    ->setMultipleBirthBoolean($is_multiple_birth)
    ->setMultipleBirthInteger(1)
    ->setBirthDate($birth_date)
    ->addCommunication($language, true)
    ->build();

$response = $ssclient->createPatient($payload);

var_dump($response->getContentAsObject());

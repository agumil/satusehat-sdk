<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderPatient;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\SSClient;
use agumil\SatuSehatSDK\Terminology\HL7\AddressType;
use agumil\SatuSehatSDK\Terminology\HL7\AddressUse;
use agumil\SatuSehatSDK\Terminology\HL7\AdministrativeGender;
use agumil\SatuSehatSDK\Terminology\HL7\IdentifierUse;
use agumil\SatuSehatSDK\Terminology\HL7\NameUse;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'staging']);

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

$address1 = new Address(
    AddressUse::CODE_HOME,
    AddressType::CODE_BOTH,
    ['Jl. Rajawali No 17'],
    'Umbulharjo',
    'Yogyakarta Kota',
    'DIY',
    '55162',
    'ID'
);
$address1_extension = new ExtensionAdministrativeCode(32, 3212, 321212, 3212122013, 4, 3);

try {
    // init payload builder organization
    $builder = new PayloadBuilderPatient();
    $payload = $builder->addIdentifier($identifier)
        ->addName($name)
        ->addAddress($address1, $address1_extension)
        ->setActive($is_active)
        ->setGender($gender)
        ->setMultipleBirthBoolean($is_multiple_birth)
        ->setMultipleBirthInteger(1)
        ->setBirthDate($birth_date)
        ->addCommunication($language, true)
        ->build();

    $response = $ssclient->createPatient($payload);

    var_dump($response->getContentAsObject());
} catch (Exception $e) {
    echo $e->getMessage();
}

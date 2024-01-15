<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderOrganization;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\CodingMulti;
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
$contact_purpose = new CodeableConcept(new CodingMulti($contact_purpose_coding));

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

// Response Result Example

/**

object(stdClass)#11 (8) {
["active"]=>
bool(true)
["address"]=>
array(1) {
[0]=>
object(stdClass)#12 (9) {
["city"]=>
string(15) "Yogyakarta Kota"
["country"]=>
string(2) "ID"
["district"]=>
string(10) "Umbulharjo"
["line"]=>
array(1) {
[0]=>
string(10) "Jl. Contoh"
}
["postalCode"]=>
string(5) "55162"
["state"]=>
string(3) "DIY"
["text"]=>
string(51) "Jl. Contoh, Umbulharjo, Yogyakarta Kota, DIY, 55162"
["type"]=>
string(4) "both"
["use"]=>
string(4) "work"
}
}
["contact"]=>
array(1) {
[0]=>
object(stdClass)#41 (2) {
["name"]=>
object(stdClass)#44 (4) {
["family"]=>
string(8) "Gumilang"
["given"]=>
array(1) {
[0]=>
string(5) "Agung"
}
["text"]=>
string(14) "Agung Gumilang"
["use"]=>
string(8) "official"
}
["purpose"]=>
object(stdClass)#22 (1) {
["coding"]=>
array(1) {
[0]=>
object(stdClass)#42 (3) {
["code"]=>
string(6) "PATINF"
["display"]=>
string(7) "Patient"
["system"]=>
string(56) "http://terminology.hl7.org/CodeSystem/contactentity-type"
}
}
}
}
}
["id"]=>
string(36) "67e1b275-4d22-43b4-b939-bc2d3e27b83d"
["identifier"]=>
array(0) {
}
["meta"]=>
object(stdClass)#23 (2) {
["lastUpdated"]=>
string(32) "2024-01-15T09:05:53.753078+00:00"
["versionId"]=>
string(26) "MTcwNTMwOTU1Mzc1MzA3ODAwMA"
}
["name"]=>
string(17) "Demo Organization"
["resourceType"]=>
string(12) "Organization"
}

 **/

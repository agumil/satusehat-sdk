<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderLocation;
use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\ExtensionLocationServiceClass;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\Helper\StrHelper;
use agumil\SatuSehatSDK\SSClient;
use agumil\SatuSehatSDK\Terminology\HL7\AddressType;
use agumil\SatuSehatSDK\Terminology\HL7\AddressUse;
use agumil\SatuSehatSDK\Terminology\HL7\ContactPointSystem;
use agumil\SatuSehatSDK\Terminology\HL7\ContactPointUse;
use agumil\SatuSehatSDK\Terminology\HL7\IdentifierUse;
use agumil\SatuSehatSDK\Terminology\HL7\LocationMode;
use agumil\SatuSehatSDK\Terminology\HL7\LocationOperationalStatus;
use agumil\SatuSehatSDK\Terminology\HL7\LocationPhysicalType;
use agumil\SatuSehatSDK\Terminology\HL7\LocationStatus;
use agumil\SatuSehatSDK\Terminology\KemKes\IdentifierSystem;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;

// init client
$ssclient = new SSClient($oauth2, ['environment' => 'development']);
$builder = new PayloadBuilderLocation();

// set identifier
$identifier = new Identifier(
    IdentifierSystem::location($parentOrganizationId),
    IdentifierUse::CODE_OFFICIAL,
    StrHelper::random(12, 'numeric')
);
$builder->addIdentifier($identifier);

// set status
$status = LocationStatus::CODE_ACTIVE;
$builder->setStatus($status);

// set operational status
$operational_status = LocationOperationalStatus::CODE_UNOCCUPIED;
$builder->setOperationalStatus($operational_status);

// set name
$name = strtoupper(StrHelper::random(6, 'alpha')) . '-Demo Location';
$builder->setName($name);

// add alias
$alias1 = strtoupper('Alias 1');
$builder->addAlias($alias1);

$alias2 = strtoupper('Alias 2');
$builder->addAlias($alias2);

// set description
$description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id nulla vitae orci porttitor tincidunt eget sit amet magna.';
$builder->setDescription($description);

// set mode
$mode = LocationMode::CODE_INSTANCE;
$builder->setMode($mode);

// add type
$type_system = 'http://terminology.kemkes.go.id/CodeSystem/location-type';
$type_code = 'RT0011';
$type_display = 'Ruangan Perawatan Intensif Pediatrik (PICU)';
$type = new CodeableConcept(null, new Coding($type_system, $type_code, $type_display));
$builder->addType($type);

// add telecom
$telsystem = ContactPointSystem::CODE_EMAIL;
$teluse = ContactPointUse::CODE_WORK;
$telval = 'dummy@example.com';

$telecom1 = new ContactPoint($telsystem, $teluse, $telval);
$builder->addTelecom($telecom1);

// add address
$addruse = AddressUse::CODE_WORK;
$addrtype = AddressType::CODE_BOTH;
$addrline = ['Jl. Pahlawan Kemerdekaan No. 17'];

$address = new Address($addruse, $addrtype, $addrline);
$builder->setAddress($address);

// set physical type
$physical_type = LocationPhysicalType::CODE_ROOM;
$builder->setPhysicalType($physical_type);

// set position
$long = 40.753056;
$lat = -73.983056;
$alt = 1000.0;
$builder->setPosition($long, $lat, $alt);

// set managing organization
// $organization = new Reference('Organization/f78187ec-ef23-4a17-ae21-b1813a6be821');
// $builder->setManagingOrganization($organization);

// set part of
// $location = new Reference('Location/2528a50b-10fd-437a-a900-b1b359afe892');
// $builder->setPartOf($location);

// set service class
$service_class = new ExtensionLocationServiceClass(ServiceClassOutpatient::CODE_KELAS_REGULER);
$builder->setServiceClass($service_class);

// add hours of operation
$days = ['mon', 'wed', 'fri'];
$all_days = false;
$opening_time = '08:00:00';
$closing_time = '17:00:00';
$builder->addHoursOfOperation($days, $all_days, $opening_time, $closing_time);

// add availability exception
$availability_exception = 'Libur nasional buka pukul 09:00:00';
$builder->setAvailabilityExceptions($availability_exception);

$response = $ssclient->createLocation($builder->build());
var_dump($response->getContentAsObject());

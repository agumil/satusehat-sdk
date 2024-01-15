<?php require __DIR__ . '/../../Auth/oauth2.php';

use agumil\SatuSehatSDK\Builder\PayloadBuilderEncounter;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Period;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Endpoint;
use agumil\SatuSehatSDK\HL7\EncounterCode;
use agumil\SatuSehatSDK\HL7\IdentifierUse;
use agumil\SatuSehatSDK\SSClient;
use agumil\SatuSehatSDK\Terminology\KemKes\EncounterStatus;

// init client
$ssclient = new SSClient($oauth2, ['base_url' => Endpoint::DEV_FHIR]);

// encounter data
$identifier = new Identifier(
    IdentifierUse::CODE_OFFICIAL,
    'http://sys-ids.kemkes.go.id/organization/8c573890-ef6a-49eb-a80d-2adaf1ff8731',
    '123456'
);
$subject = new Reference('Patient/9271060312000001', null, 'patient 1');
$status = EncounterStatus::CODE_ARRIVED;
$class = new Coding(
    EncounterCode::SYSTEM,
    EncounterCode::CODE_AMBULATORY,
    EncounterCode::getDisplayCode(EncounterCode::CODE_AMBULATORY),
);

$participant_individual = new Reference('Patient/9271060312000001');
$period_start = (new DateTime('now'))->format('c');
$period = new Period($period_start);

$location = new Reference('Location/dc01c797-547a-4e4d-97cd-4ece0630e380');

$service_provider = new Reference('Organization/8c573890-ef6a-49eb-a80d-2adaf1ff8731');

// init payload builder encounter
$payloadBuilder = new PayloadBuilderEncounter();
$payload = $payloadBuilder
    ->addIdentifier($identifier)
    ->setSubject($subject)
    ->setStatus($status)
    ->addStatusHistory($status)
    ->setClass($class)
    ->addParticipant($participant_individual)
    ->setPeriod($period)
    ->addLocation($location)
    ->setServiceProvider($service_provider)
    ->build();

$response = $ssclient->createEncounter($payload);

var_dump($response->getContentAsObject());

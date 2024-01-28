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
use agumil\SatuSehatSDK\Terminology\KemKes\IdentifierSystem;

// init client
$ssclient = new SSClient($oauth2, ['base_url' => Endpoint::DEV_FHIR]);

// encounter data
$identifier = new Identifier(
    IdentifierSystem::encounter('84c403fb-d228-4b21-9557-0c58c618b8b9'),
    IdentifierUse::CODE_OFFICIAL,
    1
);
$subject = new Reference('Patient/P02478375538', null, 'patient 1');

$status = EncounterStatus::CODE_ARRIVED;

$class = new Coding(
    EncounterCode::SYSTEM,
    EncounterCode::CODE_AMBULATORY,
    EncounterCode::getDisplayCode(EncounterCode::CODE_AMBULATORY),
);

$class_history = new Coding(
    EncounterCode::SYSTEM,
    EncounterCode::CODE_AMBULATORY,
    EncounterCode::getDisplayCode(EncounterCode::CODE_AMBULATORY),
);

$participant_individual = new Reference('Practitioner/10009880728');
$period_start = (new DateTime('now'))->format('c');
$period_end = (new DateTime('now'))->add(new DateInterval('PT30M'))->format('c');
$period = new Period($period_start, $period_end);

$location = new Reference('Location/dc01c797-547a-4e4d-97cd-4ece0630e380');

$service_provider = new Reference('Organization/84c403fb-d228-4b21-9557-0c58c618b8b9');

// init payload builder encounter
$payloadBuilder = new PayloadBuilderEncounter();
$payload = $payloadBuilder
    ->addIdentifier($identifier)
    ->setSubject($subject)
    ->setStatus($status)
    ->addStatusHistory($status, $period)
    ->setClass($class)
    ->addClassHistory($class_history, $period)
    ->addParticipant($participant_individual)
    ->setPeriod($period)
    ->addLocation($location)
    ->setServiceProvider($service_provider)
    ->build();

$response = $ssclient->createEncounter($payload);

var_dump($response->getContentAsObject(), $response->getErrorMessages());

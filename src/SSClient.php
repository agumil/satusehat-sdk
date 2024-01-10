<?php
namespace agumil\SatuSehatSDK;

use agumil\SatuSehatSDK\Auth\Oauth2;
use agumil\SatuSehatSDK\FHIR\AllergyIntolerance;
use agumil\SatuSehatSDK\FHIR\Appointment;
use agumil\SatuSehatSDK\FHIR\AppointmentResponse;
use agumil\SatuSehatSDK\FHIR\CarePlan;
use agumil\SatuSehatSDK\FHIR\ClinicalImpression;
use agumil\SatuSehatSDK\FHIR\Composition;
use agumil\SatuSehatSDK\FHIR\Condition;
use agumil\SatuSehatSDK\FHIR\DiagnosticReport;
use agumil\SatuSehatSDK\FHIR\Encounter;
use agumil\SatuSehatSDK\FHIR\EpisodeOfCare;
use agumil\SatuSehatSDK\FHIR\FamilyMemberHistory;
use agumil\SatuSehatSDK\FHIR\HealthcareService;
use agumil\SatuSehatSDK\FHIR\ImagingStudy;
use agumil\SatuSehatSDK\FHIR\Immunization;
use agumil\SatuSehatSDK\FHIR\Location;
use agumil\SatuSehatSDK\FHIR\Medication;
use agumil\SatuSehatSDK\FHIR\MedicationDispense;
use agumil\SatuSehatSDK\FHIR\MedicationRequest;
use agumil\SatuSehatSDK\FHIR\Observation;
use agumil\SatuSehatSDK\FHIR\Organization;
use agumil\SatuSehatSDK\FHIR\Patient;
use agumil\SatuSehatSDK\FHIR\Practitioner;
use agumil\SatuSehatSDK\FHIR\PractitionerRole;
use agumil\SatuSehatSDK\FHIR\Procedure;
use agumil\SatuSehatSDK\FHIR\QuestionnaireResponse;
use agumil\SatuSehatSDK\FHIR\RelatedPerson;
use agumil\SatuSehatSDK\FHIR\ServiceRequest;
use agumil\SatuSehatSDK\FHIR\Slot;
use agumil\SatuSehatSDK\FHIR\Specimen;

class SSClient
{
    private $oauth2;

    private $config;

    public function __construct(Oauth2 $oauth2, array $config = [])
    {
        $this->oauth2 = $oauth2;
        $this->config = $config;
    }

    public function getAllergyIntolerance(array $params = [])
    {
        return (new AllergyIntolerance($this->oauth2, $this->config))->getAllergyIntolerance($params);
    }

    public function getAllergyIntoleranceById(string $id)
    {
        return (new AllergyIntolerance($this->oauth2, $this->config))->getAllergyIntoleranceById($id);
    }

    public function createAllergyIntolerance($params)
    {
        return (new AllergyIntolerance($this->oauth2, $this->config))->createAllergyIntolerance($params);
    }

    public function updateAllergyIntolerance(string $id, $params)
    {
        return (new AllergyIntolerance($this->oauth2, $this->config))->updateAllergyIntolerance($id, $params);
    }

    public function getAppointment(array $params = [])
    {
        return (new Appointment($this->oauth2, $this->config))->getAppointment($params);
    }

    public function getAppointmentById(string $id)
    {
        return (new Appointment($this->oauth2, $this->config))->getAppointmentById($id);
    }

    public function createAppointment($params)
    {
        return (new Appointment($this->oauth2, $this->config))->createAppointment($params);
    }

    public function updateAppointment(string $id, $params)
    {
        return (new Appointment($this->oauth2, $this->config))->updateAppointment($id, $params);
    }

    public function getAppointmentResponse(array $params = [])
    {
        return (new AppointmentResponse($this->oauth2, $this->config))->getAppointmentResponse($params);
    }

    public function getAppointmentResponseById(string $id)
    {
        return (new AppointmentResponse($this->oauth2, $this->config))->getAppointmentResponseById($id);
    }

    public function createAppointmentResponse($params)
    {
        return (new AppointmentResponse($this->oauth2, $this->config))->createAppointmentResponse($params);
    }

    public function updateAppointmentResponse(string $id, $params)
    {
        return (new AppointmentResponse($this->oauth2, $this->config))->updateAppointmentResponse($id, $params);
    }

    public function getCarePlan(array $params = [])
    {
        return (new CarePlan($this->oauth2, $this->config))->getCarePlan($params);
    }

    public function getCarePlanById(string $id)
    {
        return (new CarePlan($this->oauth2, $this->config))->getCarePlanById($id);
    }

    public function createCarePlan($params)
    {
        return (new CarePlan($this->oauth2, $this->config))->createCarePlan($params);
    }

    public function updateCarePlan(string $id, $params)
    {
        return (new CarePlan($this->oauth2, $this->config))->updateCarePlan($id, $params);
    }

    public function getClinicalImpression(array $params = [])
    {
        return (new ClinicalImpression($this->oauth2, $this->config))->getClinicalImpression($params);
    }

    public function getClinicalImpressionById(string $id)
    {
        return (new ClinicalImpression($this->oauth2, $this->config))->getClinicalImpressionById($id);
    }

    public function createClinicalImpression($params)
    {
        return (new ClinicalImpression($this->oauth2, $this->config))->createClinicalImpression($params);
    }

    public function updateClinicalImpression(string $id, $params)
    {
        return (new ClinicalImpression($this->oauth2, $this->config))->updateClinicalImpression($id, $params);
    }

    public function getComposition(array $params = [])
    {
        return (new Composition($this->oauth2, $this->config))->getComposition($params);
    }

    public function getCompositionById(string $id)
    {
        return (new Composition($this->oauth2, $this->config))->getCompositionById($id);
    }

    public function createComposition($params)
    {
        return (new Composition($this->oauth2, $this->config))->createComposition($params);
    }

    public function updateComposition(string $id, $params)
    {
        return (new Composition($this->oauth2, $this->config))->updateComposition($id, $params);
    }

    public function getCondition(array $params = [])
    {
        return (new Condition($this->oauth2, $this->config))->getCondition($params);
    }

    public function getConditionById(string $id)
    {
        return (new Condition($this->oauth2, $this->config))->getConditionById($id);
    }

    public function createCondition($params)
    {
        return (new Condition($this->oauth2, $this->config))->createCondition($params);
    }

    public function updateCondition(string $id, $params)
    {
        return (new Condition($this->oauth2, $this->config))->updateCondition($id, $params);
    }

    public function getDiagnosticReport(array $params = [])
    {
        return (new DiagnosticReport($this->oauth2, $this->config))->getDiagnosticReport($params);
    }

    public function getDiagnosticReportById(string $id)
    {
        return (new DiagnosticReport($this->oauth2, $this->config))->getDiagnosticReportById($id);
    }

    public function createDiagnosticReport($params)
    {
        return (new DiagnosticReport($this->oauth2, $this->config))->createDiagnosticReport($params);
    }

    public function updateDiagnosticReport(string $id, $params)
    {
        return (new DiagnosticReport($this->oauth2, $this->config))->updateDiagnosticReport($id, $params);
    }

    public function getEncounter(array $params = [])
    {
        return (new Encounter($this->oauth2, $this->config))->getEncounter($params);
    }

    public function getEncounterById(string $id)
    {
        return (new Encounter($this->oauth2, $this->config))->getEncounterById($id);
    }

    public function createEncounter($params)
    {
        return (new Encounter($this->oauth2, $this->config))->createEncounter($params);
    }

    public function updateEncounter(string $id, $params)
    {
        return (new Encounter($this->oauth2, $this->config))->updateEncounter($id, $params);
    }

    public function getEpisodeOfCare(array $params = [])
    {
        return (new EpisodeOfCare($this->oauth2, $this->config))->getEpisodeOfCare($params);
    }

    public function getEpisodeOfCareById(string $id)
    {
        return (new EpisodeOfCare($this->oauth2, $this->config))->getEpisodeOfCareById($id);
    }

    public function createEpisodeOfCare($params)
    {
        return (new EpisodeOfCare($this->oauth2, $this->config))->createEpisodeOfCare($params);
    }

    public function updateEpisodeOfCare(string $id, $params)
    {
        return (new EpisodeOfCare($this->oauth2, $this->config))->updateEpisodeOfCare($id, $params);
    }

    public function getFamilyMemberHistory(array $params = [])
    {
        return (new FamilyMemberHistory($this->oauth2, $this->config))->getFamilyMemberHistory($params);
    }

    public function getFamilyMemberHistoryById(string $id)
    {
        return (new FamilyMemberHistory($this->oauth2, $this->config))->getFamilyMemberHistoryById($id);
    }

    public function createFamilyMemberHistory($params)
    {
        return (new FamilyMemberHistory($this->oauth2, $this->config))->createFamilyMemberHistory($params);
    }

    public function updateFamilyMemberHistory(string $id, $params)
    {
        return (new FamilyMemberHistory($this->oauth2, $this->config))->updateFamilyMemberHistory($id, $params);
    }

    public function getHealthcareService(array $params = [])
    {
        return (new HealthcareService($this->oauth2, $this->config))->getHealthcareService($params);
    }

    public function getHealthcareServiceById(string $id)
    {
        return (new HealthcareService($this->oauth2, $this->config))->getHealthcareServiceById($id);
    }

    public function createHealthcareService($params)
    {
        return (new HealthcareService($this->oauth2, $this->config))->createHealthcareService($params);
    }

    public function updateHealthcareService(string $id, $params)
    {
        return (new HealthcareService($this->oauth2, $this->config))->updateHealthcareService($id, $params);
    }

    public function getImagingStudy(array $params = [])
    {
        return (new ImagingStudy($this->oauth2, $this->config))->getImagingStudy($params);
    }

    public function createImagingStudy($params)
    {
        return (new ImagingStudy($this->oauth2, $this->config))->createImagingStudy($params);
    }

    public function updateImagingStudy(string $id, $params)
    {
        return (new ImagingStudy($this->oauth2, $this->config))->updateImagingStudy($id, $params);
    }

    public function getImmunization(array $params = [])
    {
        return (new Immunization($this->oauth2, $this->config))->getImmunization($params);
    }

    public function getImmunizationById(string $id)
    {
        return (new Immunization($this->oauth2, $this->config))->getImmunizationById($id);
    }

    public function createImmunization($params)
    {
        return (new Immunization($this->oauth2, $this->config))->createImmunization($params);
    }

    public function updateImmunization(string $id, $params)
    {
        return (new Immunization($this->oauth2, $this->config))->updateImmunization($id, $params);
    }

    public function getLocation(array $params = [])
    {
        return (new Location($this->oauth2, $this->config))->getLocation($params);
    }

    public function getLocationById(string $id)
    {
        return (new Location($this->oauth2, $this->config))->getLocationById($id);
    }

    public function createLocation($params)
    {
        return (new Location($this->oauth2, $this->config))->createLocation($params);
    }

    public function updateLocation(string $id, $params)
    {
        return (new Location($this->oauth2, $this->config))->updateLocation($id, $params);
    }

    public function getMedicationDispense(array $params = [])
    {
        return (new MedicationDispense($this->oauth2, $this->config))->getMedicationDispense($params);
    }

    public function getMedicationDispenseById(string $id)
    {
        return (new MedicationDispense($this->oauth2, $this->config))->getMedicationDispenseById($id);
    }

    public function createMedicationDispense($params)
    {
        return (new MedicationDispense($this->oauth2, $this->config))->createMedicationDispense($params);
    }

    public function updateMedicationDispense(string $id, $params)
    {
        return (new MedicationDispense($this->oauth2, $this->config))->updateMedicationDispense($id, $params);
    }

    public function getMedicationById(string $id)
    {
        return (new Medication($this->oauth2, $this->config))->getMedicationById($id);
    }

    public function createMedication($params)
    {
        return (new Medication($this->oauth2, $this->config))->createMedication($params);
    }

    public function updateMedication(string $id, $params)
    {
        return (new Medication($this->oauth2, $this->config))->updateMedication($id, $params);
    }

    public function getMedicationRequest(array $params = [])
    {
        return (new MedicationRequest($this->oauth2, $this->config))->getMedicationRequest($params);
    }

    public function getMedicationRequestById(string $id)
    {
        return (new MedicationRequest($this->oauth2, $this->config))->getMedicationRequestById($id);
    }

    public function createMedicationRequest($params)
    {
        return (new MedicationRequest($this->oauth2, $this->config))->createMedicationRequest($params);
    }

    public function updateMedicationRequest(string $id, $params)
    {
        return (new MedicationRequest($this->oauth2, $this->config))->updateMedicationRequest($id, $params);
    }

    public function getObservation(array $params = [])
    {
        return (new Observation($this->oauth2, $this->config))->getObservation($params);
    }

    public function getObservationById(string $id)
    {
        return (new Observation($this->oauth2, $this->config))->getObservationById($id);
    }

    public function createObservation($params)
    {
        return (new Observation($this->oauth2, $this->config))->createObservation($params);
    }

    public function updateObservation(string $id, $params)
    {
        return (new Observation($this->oauth2, $this->config))->updateObservation($id, $params);
    }

    public function getOrganization(array $params = [])
    {
        return (new Organization($this->oauth2, $this->config))->getOrganization($params);
    }

    public function getOrganizationById(string $id)
    {
        return (new Organization($this->oauth2, $this->config))->getOrganizationById($id);
    }

    public function createOrganization($params)
    {
        return (new Organization($this->oauth2, $this->config))->createOrganization($params);
    }

    public function updateOrganization(string $id, $params)
    {
        return (new Organization($this->oauth2, $this->config))->updateOrganization($id, $params);
    }

    public function getPatient(array $params = [])
    {
        return (new Patient($this->oauth2, $this->config))->getPatient($params);
    }

    public function getPatientById(string $id)
    {
        return (new Patient($this->oauth2, $this->config))->getPatientById($id);
    }

    public function createPatient($params)
    {
        return (new Patient($this->oauth2, $this->config))->createPatient($params);
    }

    public function getPractitioner(array $params = [])
    {
        return (new Practitioner($this->oauth2, $this->config))->getPractitioner($params);
    }

    public function getPractitionerById(string $id)
    {
        return (new Practitioner($this->oauth2, $this->config))->getPractitionerById($id);
    }

    public function getPractitionerRole(array $params = [])
    {
        return (new PractitionerRole($this->oauth2, $this->config))->getPractitionerRole($params);
    }

    public function getPractitionerRoleById(string $id)
    {
        return (new PractitionerRole($this->oauth2, $this->config))->getPractitionerRoleById($id);
    }

    public function createPractitionerRole($params)
    {
        return (new PractitionerRole($this->oauth2, $this->config))->createPractitionerRole($params);
    }

    public function updatePractitionerRole(string $id, $params)
    {
        return (new PractitionerRole($this->oauth2, $this->config))->updatePractitionerRole($id, $params);
    }

    public function getProcedure(array $params = [])
    {
        return (new Procedure($this->oauth2, $this->config))->getProcedure($params);
    }

    public function getProcedureById(string $id)
    {
        return (new Procedure($this->oauth2, $this->config))->getProcedureById($id);
    }

    public function createProcedure($params)
    {
        return (new Procedure($this->oauth2, $this->config))->createProcedure($params);
    }

    public function updateProcedure(string $id, $params)
    {
        return (new Procedure($this->oauth2, $this->config))->updateProcedure($id, $params);
    }

    public function getQuestionnaireResponse(array $params = [])
    {
        return (new QuestionnaireResponse($this->oauth2, $this->config))->getQuestionnaireResponse($params);
    }

    public function getQuestionnaireResponseById(string $id)
    {
        return (new QuestionnaireResponse($this->oauth2, $this->config))->getQuestionnaireResponseById($id);
    }

    public function createQuestionnaireResponse($params)
    {
        return (new QuestionnaireResponse($this->oauth2, $this->config))->createQuestionnaireResponse($params);
    }

    public function updateQuestionnaireResponse(string $id, $params)
    {
        return (new QuestionnaireResponse($this->oauth2, $this->config))->updateQuestionnaireResponse($id, $params);
    }

    public function getRelatedPerson(array $params = [])
    {
        return (new RelatedPerson($this->oauth2, $this->config))->getRelatedPerson($params);
    }

    public function getRelatedPersonById(string $id)
    {
        return (new RelatedPerson($this->oauth2, $this->config))->getRelatedPersonById($id);
    }

    public function createRelatedPerson($params)
    {
        return (new RelatedPerson($this->oauth2, $this->config))->createRelatedPerson($params);
    }

    public function updateRelatedPerson(string $id, $params)
    {
        return (new RelatedPerson($this->oauth2, $this->config))->updateRelatedPerson($id, $params);
    }

    public function getServiceRequest(array $params = [])
    {
        return (new ServiceRequest($this->oauth2, $this->config))->getServiceRequest($params);
    }

    public function getServiceRequestById(string $id)
    {
        return (new ServiceRequest($this->oauth2, $this->config))->getServiceRequestById($id);
    }

    public function createServiceRequest($params)
    {
        return (new ServiceRequest($this->oauth2, $this->config))->createServiceRequest($params);
    }

    public function updateServiceRequest(string $id, $params)
    {
        return (new ServiceRequest($this->oauth2, $this->config))->updateServiceRequest($id, $params);
    }

    public function getSlotById(string $id)
    {
        return (new Slot($this->oauth2, $this->config))->getSlotById($id);
    }

    public function createSlot($params)
    {
        return (new Slot($this->oauth2, $this->config))->createSlot($params);
    }

    public function updateSlot(string $id, $params)
    {
        return (new Slot($this->oauth2, $this->config))->updateSlot($id, $params);
    }

    public function getSpecimen(array $params = [])
    {
        return (new Specimen($this->oauth2, $this->config))->getSpecimen($params);
    }

    public function getSpecimenById(string $id)
    {
        return (new Specimen($this->oauth2, $this->config))->getSpecimenById($id);
    }

    public function createSpecimen($params)
    {
        return (new Specimen($this->oauth2, $this->config))->createSpecimen($params);
    }

    public function updateSpecimen(string $id, $params)
    {
        return (new Specimen($this->oauth2, $this->config))->updateSpecimen($id, $params);
    }
}

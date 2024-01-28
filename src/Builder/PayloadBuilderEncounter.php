<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\Duration;
use agumil\SatuSehatSDK\DataType\ExtensionServiceClass;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Period;
use agumil\SatuSehatSDK\DataType\Reference;

class PayloadBuilderEncounter
{
    private static $resource_type = 'Encounter';

    private array $identifier;

    private string $status;

    private array $status_history;

    private array $class;

    private array $class_history;

    private array $type;

    private array $service_type;

    private array $priority;

    private array $subject;

    private array $episode_of_care;

    private array $based_on;

    private array $participant;

    private array $appoinment;

    private array $period;

    private array $length;

    private array $reason_code;

    private array $reason_reference;

    private array $diagnosis;

    private array $account;

    private array $hospitalization;

    private array $location;

    private array $service_provider;

    private array $part_of;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function addStatusHistory(string $status, Period $period)
    {
        $data['status'] = $status;

        if (isset($period)) {
            $data['period'] = $period->toArray();
        }

        $this->status_history[] = $data;

        return $this;
    }

    public function setClass(Coding $class)
    {
        $this->class = $class->toArray();

        return $this;
    }

    public function addClassHistory(Coding $class, Period $period)
    {
        $data = [
            'class' => $class->toArray(),
            'period' => $period->toArray(),
        ];

        $this->class_history[] = $data;

        return $this;
    }

    public function addType(CodeableConcept $type)
    {
        $this->type[] = $type->toArray();

        return $this;
    }

    public function setServiceType(CodeableConcept $serviceType)
    {
        $this->service_type = $serviceType->toArray();

        return $this;
    }

    public function setPriority(CodeableConcept $priority)
    {
        $this->priority = $priority->toArray();

        return $this;
    }

    public function setSubject(Reference $subject)
    {
        $this->subject = $subject->toArray();

        return $this;
    }

    public function addEpisodeOfCare(Reference $episodeOfCare)
    {
        $this->episode_of_care[] = $episodeOfCare->toArray();

        return $this;
    }

    public function addBasedOn(Reference $basedOn)
    {
        $this->based_on[] = $basedOn->toArray();

        return $this;
    }

    public function addParticipant(Reference $individual, ?Period $period = null, CodeableConcept...$type)
    {
        $types = [];
        foreach ($type as $val) {
            $types[] = $val->toArray();
        }

        $data['individual'] = $individual->toArray();

        if (isset($period)) {
            $data['period'] = $period->toArray();
        }

        if (!empty($types)) {
            $data['type'] = $types;
        }

        $this->participant[] = $data;

        return $this;
    }

    public function addAppoinment(Reference $appoinment)
    {
        $this->appoinment[] = $appoinment->toArray();

        return $this;
    }

    public function setPeriod(Period $period)
    {
        $this->period = $period->toArray();

        return $this;
    }

    public function setLength(Duration $length)
    {
        $this->length = $length->toArray();

        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode)
    {
        $this->reason_code[] = $reasonCode->toArray();

        return $this;
    }

    public function addReasonReference(Reference $reasonReference)
    {
        $this->reason_reference[] = $reasonReference->toArray();

        return $this;
    }

    public function addDiagnosis(Reference $condition, ?CodeableConcept $use = null, ?int $rank = null)
    {
        $data['condition'] = $condition->toArray();

        if (isset($use)) {
            $data['use'] = $use->toArray();
        }

        if (isset($rank)) {
            $data['rank'] = $rank;
        }

        $this->diagnosis[] = $data;

        return $this;
    }

    public function addAccount(Reference $account)
    {
        $this->account[] = $account->toArray();

        return $this;
    }

    public function setHospitalizationPreAdmissionIdentifier(Identifier $preAdmissionIdentifier)
    {
        $this->hospitalization['preAdmissionIdentifier'] = $preAdmissionIdentifier->toArray();

        return $this;
    }

    public function setHospitalizationOrigin(Reference $origin)
    {
        $this->hospitalization['origin'] = $origin->toArray();

        return $this;
    }

    public function setHospitalizationAdmitSource(CodeableConcept $admitSource)
    {
        $this->hospitalization['admitSource'] = $admitSource->toArray();

        return $this;
    }

    public function setHospitalizationDestination(CodeableConcept $destination)
    {
        $this->hospitalization['destination'] = $destination->toArray();

        return $this;
    }

    public function setHospitalizationDischargeDisposition(CodeableConcept $dischargeDisposition)
    {
        $this->hospitalization['dischargeDisposition'] = $dischargeDisposition->toArray();

        return $this;
    }

    public function setHospitalizationReAdmission(CodeableConcept $reAdmission)
    {
        $this->hospitalization['reAdmission'] = $reAdmission->toArray();

        return $this;
    }

    public function addHospitalizationDietPreference(CodeableConcept $dietPreference)
    {
        $this->hospitalization['dietPreference'][] = $dietPreference->toArray();

        return $this;
    }

    public function addHospitalizationSpecialCourtesy(CodeableConcept $specialCourtesy)
    {
        $this->hospitalization['specialCourtesy'][] = $specialCourtesy->toArray();

        return $this;
    }

    public function addHospitalizationSpecialArrangement(CodeableConcept $specialArrangement)
    {
        $this->hospitalization['specialArrangement'][] = $specialArrangement->toArray();

        return $this;
    }

    public function addLocation(Reference $location, ?string $status = null, ?CodeableConcept $physicalType = null, ?Period $period = null, ?ExtensionServiceClass $serviceClass = null)
    {
        $data['location'] = $location->toArray();

        if (isset($status)) {
            $data['status'] = $status;
        }

        if (isset($physicalType)) {
            $data['physicalType'] = $physicalType->toArray();
        }

        if (isset($period)) {
            $data['period'] = $period->toArray();
        }

        if (isset($serviceClass)) {
            $data['extension'] = $serviceClass->toArray();
        }

        $this->location[] = $data;

        return $this;
    }

    public function setServiceProvider(Reference $serviceProvider)
    {
        $this->service_provider = $serviceProvider->toArray();

        return $this;
    }

    public function setPartOf(Reference $partOf)
    {
        $this->part_of = $partOf->toArray();

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->status)) {
            $data['status'] = $this->status;
        }

        if (!empty($this->status_history)) {
            $data['statusHistory'] = $this->status_history;
        }

        if (!empty($this->class)) {
            $data['class'] = $this->class;
        }

        if (!empty($this->class_history)) {
            $data['classHistory'] = $this->class_history;
        }

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->service_type)) {
            $data['serviceType'] = $this->service_type;
        }

        if (!empty($this->priority)) {
            $data['priority'] = $this->priority;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->episode_of_care)) {
            $data['episodeOfCare'] = $this->episode_of_care;
        }

        if (!empty($this->based_on)) {
            $data['basedOn'] = $this->based_on;
        }

        if (!empty($this->participant)) {
            $data['participant'] = $this->participant;
        }

        if (!empty($this->appoinment)) {
            $data['appoinment'] = $this->appoinment;
        }

        if (!empty($this->period)) {
            $data['period'] = $this->period;
        }

        if (!empty($this->length)) {
            $data['length'] = $this->length;
        }

        if (!empty($this->reason_code)) {
            $data['reasonCode'] = $this->reason_code;
        }

        if (!empty($this->reason_reference)) {
            $data['reasonReference'] = $this->reason_reference;
        }

        if (!empty($this->diagnosis)) {
            $data['diagnosis'] = $this->diagnosis;
        }

        if (!empty($this->account)) {
            $data['account'] = $this->account;
        }

        if (!empty($this->hospitalization)) {
            $data['hospitalization'] = $this->hospitalization;
        }

        if (!empty($this->location)) {
            $data['location'] = $this->location;
        }

        if (!empty($this->service_provider)) {
            $data['serviceProvider'] = $this->service_provider;
        }

        if (!empty($this->part_of)) {
            $data['partOf'] = $this->part_of;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

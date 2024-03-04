<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\Dosage;
use agumil\SatuSehatSDK\DataType\Duration;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Period;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\DataType\SimpleQuantity;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\HL7\MedicationRequestCategory;
use agumil\SatuSehatSDK\Terminology\HL7\MedicationRequestCourseOfTherapyType;
use agumil\SatuSehatSDK\Terminology\HL7\MedicationRequestIntent;
use agumil\SatuSehatSDK\Terminology\HL7\MedicationRequestPriority;
use agumil\SatuSehatSDK\Terminology\HL7\MedicationRequestStatus;

class PayloadBuilderMedicationRequest
{
    private static $resource_type = 'MedicationRequest';

    private array $identifier;

    private string $status;

    private string $intent;

    private array $category;

    private string $priority;

    private array $medication_reference;

    private array $subject;

    private array $encounter;

    private string $authored_on;

    private array $requester;

    private array $reason_code;

    private array $course_of_therapy_type;

    private array $dosage_instruction;

    private array $dispense_request;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setStatus(string $status)
    {
        ValidatorHelper::in('status', $status, MedicationRequestStatus::getCodes());
        $this->status = $status;

        return $this;
    }

    public function setIntent(string $intent)
    {
        ValidatorHelper::in('intent', $intent, MedicationRequestIntent::getCodes());
        $this->intent = $intent;

        return $this;
    }

    public function addCategory(CodeableConcept | string $category)
    {
        if (!($category instanceof CodeableConcept)) {
            ValidatorHelper::in('category', $category, MedicationRequestCategory::getCodes());

            $system = MedicationRequestCategory::SYSTEM;
            $display = MedicationRequestCategory::getDisplayCode($category);

            $this->category[] = (new CodeableConcept($display, new Coding($system, $category, $display)))->toArray();
        } else {
            $this->category[] = $category->toArray();
        }

        return $this;
    }

    public function setPriority(string $priority)
    {
        ValidatorHelper::in('priority', $priority, MedicationRequestPriority::getCodes());
        $this->priority = $priority;

        return $this;
    }

    public function setMedicationReference(Reference $medicationReference)
    {
        $this->medication_reference = $medicationReference->toArray();

        return $this;
    }

    public function setSubject(Reference $subject)
    {
        $this->subject = $subject->toArray();

        return $this;
    }

    public function setEncounter(Reference $encounter)
    {
        $this->encounter = $encounter->toArray();

        return $this;
    }

    public function setAuthoredOn(string $authoredOn)
    {
        $authoredOn = ValidatorHelper::dateTime($authoredOn);
        $this->authored_on = $authoredOn;

        return $this;
    }

    public function setRequester(Reference $requester)
    {
        $this->requester = $requester->toArray();

        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode)
    {
        $this->reason_code[] = $reasonCode->toArray();

        return $this;
    }

    public function setCourseOfTherapyType(CodeableConcept | string $courseOfTherapyType)
    {
        if (!($courseOfTherapyType instanceof CodeableConcept)) {
            ValidatorHelper::in('courseOfTherapyType', $courseOfTherapyType, MedicationRequestCourseOfTherapyType::getCodes());

            $system = MedicationRequestCourseOfTherapyType::SYSTEM;
            $display = MedicationRequestCourseOfTherapyType::getDisplayCode($courseOfTherapyType);

            $this->course_of_therapy_type = (new CodeableConcept($display, new Coding($system, $courseOfTherapyType, $display)))->toArray();
        } else {
            $this->course_of_therapy_type = $courseOfTherapyType->toArray();
        }

        return $this;
    }

    public function addDosageInstruction(Dosage $dosageInstruction)
    {
        $this->dosage_instruction[] = $dosageInstruction->toArray();

        return $this;
    }

    public function setDispenseRequset(Duration $dispenseInterval, Period $validityPeriod, int $numberOfRepeatsAllowed, SimpleQuantity $quantity, Duration $expectedSupplyDuration, Reference $performer)
    {
        $this->dispense_request = [
            'dispenseInterval' => $dispenseInterval->toArray(),
            'validityPeriod' => $validityPeriod->toArray(),
            'numberOfRepeatsAllowed' => $numberOfRepeatsAllowed,
            'quantity' => $quantity->toArray(),
            'expectedSupplyDuration' => $expectedSupplyDuration->toArray(),
            'performer' => $performer->toArray(),
        ];

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

        if (!empty($this->intent)) {
            $data['intent'] = $this->intent;
        }

        if (!empty($this->category)) {
            $data['category'] = $this->category;
        }

        if (!empty($this->priority)) {
            $data['priority'] = $this->priority;
        }

        if (!empty($this->medication_reference)) {
            $data['medicationReference'] = $this->medication_reference;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->encounter)) {
            $data['encounter'] = $this->encounter;
        }

        if (!empty($this->authored_on)) {
            $data['authoredOn'] = $this->authored_on;
        }

        if (!empty($this->requester)) {
            $data['requester'] = $this->requester;
        }

        if (!empty($this->reason_code)) {
            $data['reasonCode'] = $this->reason_code;
        }

        if (!empty($this->course_of_therapy_type)) {
            $data['courseOfTherapyType'] = $this->course_of_therapy_type;
        }

        if (!empty($this->dosage_instruction)) {
            $data['dosageInstruction'] = $this->dosage_instruction;
        }

        if (!empty($this->dispense_request)) {
            $data['dispenseRequest'] = $this->dispense_request;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

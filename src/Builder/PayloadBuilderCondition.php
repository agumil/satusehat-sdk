<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\Annotation;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use DateTime;

class PayloadBuilderCondition
{
    private static $resource_type = 'Condition';

    private array $identifier;

    private array $code;

    private array $subject;

    private array $encounter;

    private array $clinical_status;

    private array $verification_status;

    private array $category;

    private array $severity;

    private string $recorded_date;

    private array $note;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setCode(CodeableConcept $code)
    {
        $this->code = $code->toArray();

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

    public function setClinicalStatus(CodeableConcept $clinicalStatus)
    {
        $this->clinical_status = $clinicalStatus->toArray();

        return $this;
    }

    public function setVerificationStatus(CodeableConcept $verificationStatus)
    {
        $this->verification_status = $verificationStatus->toArray();

        return $this;
    }

    public function addCategory(CodeableConcept $category)
    {
        $this->category[] = $category->toArray();

        return $this;
    }

    public function setSeverity(CodeableConcept $severity)
    {
        $this->severity = $severity->toArray();

        return $this;
    }

    public function setRecordedDate(string $recordedDate)
    {
        $epoch = strtotime($recordedDate);
        if ($epoch === false) {
            throw new SSDataTypeException('Parameter recordedDate is unparseable by strtotime. Please provide a valid date, dateTime, or time.');
        }

        $this->recorded_date = (new DateTime())->setTimestamp($epoch)->format('c');

        return $this;
    }

    public function addNote(Annotation $note)
    {
        $this->note[] = $note->toArray();

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->code)) {
            $data['code'] = $this->code;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->encounter)) {
            $data['encounter'] = $this->encounter;
        }

        if (!empty($this->clinical_status)) {
            $data['clinicalStatus'] = $this->clinical_status;
        }

        if (!empty($this->verification_status)) {
            $data['verificationStatus'] = $this->verification_status;
        }

        if (!empty($this->category)) {
            $data['category'] = $this->category;
        }

        if (!empty($this->severity)) {
            $data['severity'] = $this->severity;
        }

        if (!empty($this->recorded_date)) {
            $data['recordedDate'] = $this->recorded_date;
        }

        if (!empty($this->note)) {
            $data['note'] = $this->note;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

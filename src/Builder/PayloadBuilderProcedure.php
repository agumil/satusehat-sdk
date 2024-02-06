<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\Age;
use agumil\SatuSehatSDK\DataType\Annotation;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Period;
use agumil\SatuSehatSDK\DataType\Range;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\HL7\EventStatus;
use DateTime;
use DateTimeZone;

class PayloadBuilderProcedure
{
    private static $resource_type = 'Procedure';

    private array $identifier;

    private string $status;

    private array $code;

    private array $subject;

    private array $encounter;

    private array $recorder;

    private array $asserter;

    private array $performed;

    private array $performer;

    private array $body_site;

    private array $reason_code;

    private array $note;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setStatus(string $status)
    {
        $allowed_status = EventStatus::getCodes();
        if (!in_array($status, $allowed_status)) {
            $allowed_status = implode(',', $allowed_status);
            throw new SSDataTypeException("category must be one of {$allowed_status}");
        }

        $this->status = $status;

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

    public function setRecorder(Reference $recorder)
    {
        $this->recorder = $recorder->toArray();

        return $this;
    }

    public function setAsserter(Reference $asserter)
    {
        $this->asserter = $asserter->toArray();

        return $this;
    }

    public function setPerformed(Period | Age | Range | string $performed)
    {
        $data = [];

        if ($performed instanceof Period) {
            $data['performedPeriod'] = $performed->toArray();
        } elseif ($performed instanceof Age) {
            $data['performedAge'] = $performed->toArray();
        } elseif ($performed instanceof Range) {
            $data['performedRange'] = $performed->toArray();
        } elseif (is_string($performed) && strtotime($performed) !== false) {
            $data['performedDateTime'] = (new DateTime($performed))->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('c');
        } elseif (is_string($performed)) {
            $data['performedString'] = $performed;
        } else {
            throw new SSDataTypeException('Value data type is not supported');
        }

        $this->performed = $data;

        return $this;
    }

    public function addPerformer(Reference $actor, ?CodeableConcept $function = null, ?Reference $on_behalf_of = null)
    {
        $data['actor'] = $actor->toArray();

        if (isset($function)) {
            $data['function'] = $function->toArray();
        }

        if (isset($on_behalf_of)) {
            $data['onBehalfOf'] = $on_behalf_of->toArray();
        }

        $this->performer[] = $data;

        return $this;
    }

    public function addReasonCode(CodeableConcept $reason_code)
    {
        $this->reason_code[] = $reason_code->toArray();

        return $this;
    }

    public function addBodySite(CodeableConcept $body_site)
    {
        $this->body_site[] = $body_site->toArray();

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

        if (!empty($this->status)) {
            $data['status'] = $this->status;
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

        if (!empty($this->recorder)) {
            $data['recorder'] = $this->recorder;
        }

        if (!empty($this->asserter)) {
            $data['asserter'] = $this->asserter;
        }

        if (!empty($this->performed)) {
            $key = key($this->performed);
            $value = $this->performed[$key];

            $data[$key] = $value;
        }

        if (!empty($this->performer)) {
            $data['performer'] = $this->performer;
        }

        if (!empty($this->reason_code)) {
            $data['reasonCode'] = $this->reason_code;
        }

        if (!empty($this->body_site)) {
            $data['bodySite'] = $this->body_site;
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

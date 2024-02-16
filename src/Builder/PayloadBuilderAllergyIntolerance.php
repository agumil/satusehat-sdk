<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\BundleAnnotation;
use agumil\SatuSehatSDK\DataType\BundleCodeableConcept;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\HL7\AllergyIntoleranceCategory;
use agumil\SatuSehatSDK\Terminology\HL7\AllergyIntoleranceType;

class PayloadBuilderAllergyIntolerance
{
    private static $resource_type = 'AllergyIntolerance';

    private array $identifier;

    private array $clinical_status;

    private array $verification_status;

    private string $type;

    private array $category;

    private array $code;

    private array $patient;

    private array $encounter;

    private array $reaction;

    private array $recorder;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

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

    public function setType(string $type)
    {
        $status = AllergyIntoleranceType::getCodes();
        if (!in_array($type, $status)) {
            $status = implode(',', $status);
            throw new SSDataTypeException("type must be one of {$status}");
        }

        $this->type = $type;

        return $this;
    }

    public function addCategory(string $category)
    {
        $status = AllergyIntoleranceCategory::getCodes();
        if (!in_array($category, $status)) {
            $status = implode(',', $status);
            throw new SSDataTypeException("category must be one of {$status}");
        }

        $this->category[] = $category;

        return $this;
    }

    public function setCode(CodeableConcept $code)
    {
        $this->code = $code->toArray();

        return $this;
    }

    public function setPatient(Reference $patient)
    {
        $this->patient = $patient->toArray();

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

    public function addReaction(BundleCodeableConcept $manifestation, ?BundleCodeableConcept $substance = null, string | array $description = null, ?string $onset = null, ?string $severity = null, ?CodeableConcept $exposureRoute = null, ?BundleAnnotation $annotation = null)
    {
        $data['manifestation'] = $manifestation->toArray();

        if (isset($substance)) {
            $data['substance'] = $substance->toArray();
        }

        if (isset($description)) {
            if (is_string($description)) {
                $data['description'][] = $description;
            } else {
                $data['description'] = $description;
            }
        }

        if (isset($onset)) {
            $data['onset'] = ValidatorHelper::dateTime('onset', $onset);
        }

        if (isset($severity)) {
            $data['severity'] = $severity;
        }

        if (isset($exposureRoute)) {
            $data['exposureRoute'] = $exposureRoute->toArray();
        }

        if (isset($annotation)) {
            $data['annotation'] = $annotation->toArray();
        }

        $this->reaction[] = $data;

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->clinical_status)) {
            $data['clinicalStatus'] = $this->clinical_status;
        }

        if (!empty($this->verification_status)) {
            $data['verificationStatus'] = $this->verification_status;
        }

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->category)) {
            $data['category'] = $this->category;
        }

        if (!empty($this->code)) {
            $data['code'] = $this->code;
        }

        if (!empty($this->patient)) {
            $data['patient'] = $this->patient;
        }

        if (!empty($this->recorder)) {
            $data['recorder'] = $this->recorder;
        }

        if (!empty($this->encounter)) {
            $data['encounter'] = $this->encounter;
        }

        if (!empty($this->reaction)) {
            $data['reaction'] = $this->reaction;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

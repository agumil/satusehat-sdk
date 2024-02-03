<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\BundleAnnotation;
use agumil\SatuSehatSDK\DataType\BundleCodeableConcept;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\HL7\AllergyIntoleranceCategory;
use agumil\SatuSehatSDK\HL7\AllergyIntoleranceClinicalStatus;
use agumil\SatuSehatSDK\HL7\AllergyIntoleranceType;
use agumil\SatuSehatSDK\HL7\AllergyIntoleranceVerificationStatus;

class PayloadBuilderAllergyIntolerance
{
    private static $resource_type = 'AllergyIntolerance';

    private array $identifier;

    private string $clinical_status;

    private string $verification_status;

    private string $type;

    private array $category;

    private array $code;

    private array $patient;

    private array $encounter;

    private array $reaction;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setClinicalStatus(string $clinicalStatus)
    {
        $status = AllergyIntoleranceClinicalStatus::getCodes();
        if (!in_array($clinicalStatus, $status)) {
            $status = implode(',', $status);
            throw new SSDataTypeException("clinicalStatus must be one of {$status}");
        }

        $this->clinical_status = $clinicalStatus;

        return $this;
    }

    public function setVerificationStatus(string $verificationStatus)
    {
        $status = AllergyIntoleranceVerificationStatus::getCodes();
        if (!in_array($verificationStatus, $status)) {
            $status = implode(',', $status);
            throw new SSDataTypeException("verificationStatus must be one of {$status}");
        }

        $this->verification_status = $verificationStatus;

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

    public function addCode(CodeableConcept $code)
    {
        $this->code[] = $code->toArray();

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
            $isValid = ValidatorHelper::validDateTime($onset);
            if (!$isValid) {
                throw new SSDataTypeException('Parameter onset is unparseable by strtotime. Please provide a valid date.');
            }

            $data['onset'] = $onset;
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

<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\Address;
use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\CodeableConceptMulti;
use agumil\SatuSehatSDK\DataType\ContactPoint;
use agumil\SatuSehatSDK\DataType\ContactPointMulti;
use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;
use agumil\SatuSehatSDK\DataType\HumanName;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;
use DateTime;

class PayloadBuilderPatient
{
    private static $resource_type = 'Patient';

    private bool $active;

    private array $name;

    private string $gender;

    private string $birth_date;

    private bool $deceased_boolean;

    private string $deceased_datetime;

    private array $identifier;

    private array $telecom;

    private array $address;

    private array $marital_status;

    private bool $multiple_birth_boolean;

    private int $multiple_birth_integer;

    private array $contact;

    private array $communication;

    private array $general_practitioner;

    private array $managing_organization;

    private array $link;

    public function setActive(bool $isActive)
    {
        $this->active = boolval($isActive);

        return $this;
    }

    public function setGender(string $gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function setBirthDate($date)
    {
        $epoch = strtotime($date);
        if (!$epoch) {
            return $this;
        }

        $this->birth_date = (new DateTime())->setTimestamp($epoch)->format('Y-m-d');

        return $this;
    }

    public function setDeceasedBoolean(bool $isDeceased)
    {
        $this->deceased_boolean = $isDeceased;

        return $this;
    }

    public function setDeceasedDateTime(string $dateTime)
    {
        $this->deceased_datetime = $dateTime;

        return $this;
    }

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function addName(HumanName $name)
    {
        $this->name[] = $name->toArray();

        return $this;
    }

    public function addTelecom(ContactPoint $telecom)
    {
        $this->telecom[] = $telecom->toArray();

        return $this;
    }

    public function addAddress(Address $address, ExtensionAdministrativeCode ...$extensions)
    {
        $dataAddress = $address->toArray();

        foreach ($extensions as $extension) {
            $dataAddress['extension'][] = $extension;
        }

        $this->address[] = $dataAddress;

        return $this;
    }

    public function setMaritalStatus(CodeableConcept $maritalStatus)
    {
        $this->marital_status = $maritalStatus->toArray();

        return $this;
    }

    public function setMultipleBirthBoolean(bool $isMultipleBirth)
    {
        $this->multiple_birth_boolean = $isMultipleBirth;

        return $this;
    }

    public function setMultipleBirthInteger(int $numMultipleBirth)
    {
        $this->multiple_birth_integer = $numMultipleBirth;

        return $this;
    }

    public function addContact(HumanName $name, ContactPointMulti $telecom, CodeableConceptMulti $relationship)
    {
        $this->contact[] = [
            'relationship' => $relationship->toArray(),
            'name' => $name->toArray(),
            'telecom' => $telecom->toArray(),
        ];

        return $this;
    }

    public function addCommunication(CodeableConcept $language, bool $preferred)
    {
        $this->communication[] = [
            'language' => $language->toArray(),
            'preferred' => $preferred,
        ];

        return $this;
    }

    public function addGeneralPractitioner(Reference $generalPractitioner)
    {
        $this->general_practitioner[] = $generalPractitioner->toArray();

        return $this;
    }

    public function setManagingOrganization(Reference $managingOrganization)
    {
        $this->managing_organization = $managingOrganization->toArray();

        return $this;
    }

    public function addLink(string $type, Reference $other)
    {
        $this->link[] = [
            'type' => $type,
            'other' => $other->toArray(),
        ];

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->active)) {
            $data['active'] = $this->active;
        }

        if (!empty($this->name)) {
            $data['name'] = $this->name;
        }

        if (!empty($this->gender)) {
            $data['gender'] = $this->gender;
        }

        if (!empty($this->birth_date)) {
            $data['birthDate'] = $this->birth_date;
        }

        if (!empty($this->deceased_boolean)) {
            $data['deceasedBoolean'] = $this->deceased_boolean;
        }

        if (!empty($this->deceased_datetime)) {
            $data['deceasedDateTime'] = $this->deceased_datetime;
        }

        if (!empty($this->telecom)) {
            $data['telecom'] = $this->telecom;
        }

        if (!empty($this->address)) {
            $data['address'] = $this->address;
        }

        if (!empty($this->marital_status)) {
            $data['maritalStatus'] = $this->marital_status;
        }

        if (!empty($this->multiple_birth_boolean)) {
            $data['multipleBirthBoolean'] = $this->multiple_birth_boolean;
        }

        if (!empty($this->multiple_birth_integer)) {
            $data['multipleBirthInteger'] = $this->multiple_birth_integer;
        }

        if (!empty($this->contact)) {
            $data['contact'] = $this->contact;
        }

        if (!empty($this->communication)) {
            $data['communication'] = $this->communication;
        }

        if (!empty($this->general_practitioner)) {
            $data['generalPractitioner'] = $this->general_practitioner;
        }

        if (!empty($this->managing_organization)) {
            $data['managingOrganization'] = $this->managing_organization;
        }

        if (!empty($this->link)) {
            $data['link'] = $this->link;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

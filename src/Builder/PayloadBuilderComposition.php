<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Narrative;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class PayloadBuilderComposition
{
    private static $resource_type = 'Composition';

    private array $identifier;

    private string $status;

    private array $type;

    private array $subject;

    private string $date;

    private array $author;

    private string $title;

    private array $category;

    private array $encounter;

    private string $confidentiality;

    private array $custodian;

    private array $section;

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

    public function setType(CodeableConcept $type)
    {
        $this->type = $type->toArray();

        return $this;
    }

    public function setSubject(Reference $subject)
    {
        $this->subject = $subject->toArray();

        return $this;
    }

    public function setDate(string $dateTime)
    {
        $this->date = ValidatorHelper::dateTime($dateTime);

        return $this;
    }

    public function addAuthor(Reference $author)
    {
        $this->author[] = $author->toArray();

        return $this;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function addCategory(CodeableConcept $category)
    {
        $this->category[] = $category->toArray();

        return $this;
    }

    public function setEncounter(Reference $encounter)
    {
        $this->encounter = $encounter->toArray();

        return $this;
    }

    public function setConfidentiality(string $confidentiality)
    {
        $this->confidentiality = $confidentiality;

        return $this;
    }

    public function setCustodian(Reference $custodian)
    {
        $this->custodian = $custodian->toArray();

        return $this;
    }

    public function addSection(CodeableConcept $code, string $title, Narrative $text)
    {
        $data['code'] = $code->toArray();
        $data['title'] = $title;
        $data['text'] = $text->toArray();

        $this->section[] = $data;

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

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->subject)) {
            $data['subject'] = $this->subject;
        }

        if (!empty($this->date)) {
            $data['date'] = $this->date;
        }

        if (!empty($this->author)) {
            $data['author'] = $this->author;
        }

        if (!empty($this->title)) {
            $data['title'] = $this->title;
        }

        if (!empty($this->category)) {
            $data['category'] = $this->category;
        }

        if (!empty($this->encounter)) {
            $data['encounter'] = $this->encounter;
        }

        if (!empty($this->confidentiality)) {
            $data['confidentiality'] = $this->confidentiality;
        }

        if (!empty($this->custodian)) {
            $data['custodian'] = $this->custodian;
        }

        if (!empty($this->section)) {
            $data['section'] = $this->section;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Reference;

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
        $this->date = $dateTime;

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

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

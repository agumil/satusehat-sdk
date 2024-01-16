<?php
namespace agumil\SatuSehatSDK\DataType;

class CodeableConcept
{
    private array $codings;

    private ?string $text;

    public function __construct(?string $text = null, Coding ...$codings)
    {
        $this->codings = $codings;
        $this->text = $text;
    }

    public function toArray(): array
    {
        foreach ($this->codings as $coding) {
            $data['coding'][] = $coding->toArray();
        }

        if (isset($this->text)) {
            $data['text'] = $this->text;
        }

        return $data;
    }
}

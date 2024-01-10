<?php
namespace agumil\SatuSehatSDK\DataType;

class CodeableConcept
{
    private CodingMulti $coding_multi;

    private ?string $text;

    public function __construct(CodingMulti $codingMulti, ?string $text = null)
    {
        $this->coding_multi = $codingMulti;
        $this->text = $text;
    }

    public function toArray(): array
    {
        $data['coding'] = $this->coding_multi->toArray();

        if (!empty($this->text)) {
            $data['text'] = $this->text;
        }

        return $data;
    }
}

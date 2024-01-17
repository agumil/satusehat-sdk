<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use DateTime;

class Annotation
{
    private ?Reference $author;

    private ?string $time;

    private ?string $text;

    public function __construct(?Reference $author = null, string $time = null, ?string $text = null)
    {
        $this->author = $author;
        $this->time = $time;
        $this->text = $text;

        $isValid = ValidatorHelper::validDateTime($time);
        if (!$isValid) {
            throw new SSDataTypeException('Parameter time is unparseable by strtotime. Please provide a valid date.');
        }
    }

    public function toArray(): array
    {
        $data = [];

        if (isset($this->author)) {
            $data['authorReference'] = $this->author->toArray();
        }

        if (isset($this->time)) {
            $data['time'] = (new DateTime($this->time))->format('c');
        }

        if (isset($this->text)) {
            $data['text'] = $this->text;
        }

        return $data;
    }
}

<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use DateTime;

class Annotation
{
    private string $text;

    private ?string $time;

    private ?Reference $authorReference;

    private ?string $authorString;

    public function __construct(string $text, ?string $dateTime = null, ?Reference $authorReference = null, ?string $authorString = null)
    {
        if (!empty($dateTime)) {
            $isValid = ValidatorHelper::validDateTime($dateTime);
            if (!$isValid) {
                throw new SSDataTypeException('Parameter dateTime is unparseable by strtotime. Please provide a valid date.');
            }
        }

        $this->text = $text;
        $this->time = $dateTime;
        $this->authorReference = $authorReference;
        $this->authorString = $authorString;
    }

    public function toArray(): array
    {
        $data['text'] = $this->text;

        if (isset($this->time)) {
            $data['time'] = (new DateTime($this->time))->format('c');
        }

        if (isset($this->authorReference)) {
            $data['authorReference'] = $this->authorReference->toArray();
        }

        if (isset($this->authorString)) {
            $data['authorString'] = $this->authorString;
        }

        return $data;
    }
}

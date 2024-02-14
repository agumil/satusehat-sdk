<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class Period
{
    private ?string $start;

    private ?string $end;

    public function __construct(?string $startDateTime = null, ?string $endDateTime = null)
    {
        if (!empty($startDateTime)) {
            $startDateTime = ValidatorHelper::dateTime($startDateTime);
            $epochStart = strtotime($startDateTime);
        }

        if (!empty($endDateTime)) {
            $endDateTime = ValidatorHelper::dateTime($endDateTime);
            $epochEnd = strtotime($endDateTime);
        }

        if (!empty($startDateTime) && !empty($endDateTime)) {
            if ($epochEnd < $epochStart) {
                throw new SSDataTypeException('startDateTime should not lower than endDateTime.');
            }
        }

        $this->start = $startDateTime;
        $this->end = $endDateTime;
    }

    public function toArray(): array
    {
        $data = [];
        if (!empty($this->start)) {
            $data['start'] = $this->start;
        }

        if (!empty($this->end)) {
            $data['end'] = $this->end;
        }

        return $data;
    }
}

<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class Period
{
    private ?string $start;

    private ?string $end;

    public function __construct(?string $startDateTime = null, ?string $endDateTime = null)
    {
        if (!empty($startDateTime) && !empty($endDateTime)) {
            throw new SSDataTypeException('Both startDateTime and endDateTime is not allowed to be empty.');
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

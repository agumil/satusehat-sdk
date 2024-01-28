<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use DateTime;
use DateTimeZone;

class Period
{
    private ?string $start;

    private ?string $end;

    private string $timezone;

    public function __construct(?string $startDateTime = null, ?string $endDateTime = null, string $tz = 'Asia/Jakarta')
    {
        if (!empty($startDateTime)) {
            $epochStart = strtotime($startDateTime);
            if ($epochStart === false) {
                throw new SSDataTypeException('Parameter startDateTime is unparseable by strtotime. Please provide a valid date.');
            }
        }

        if (!empty($endDateTime)) {
            $epochEnd = strtotime($endDateTime);
            if ($epochEnd === false) {
                throw new SSDataTypeException('Parameter endDateTime is unparseable by strtotime. Please provide a valid date.');
            }
        }

        if (!empty($startDateTime) && !empty($endDateTime)) {
            if ($epochEnd < $epochStart) {
                throw new SSDataTypeException('startDateTime should not lower than endDateTime.');
            }
        }

        $this->start = $startDateTime;
        $this->end = $endDateTime;
        $this->timezone = $tz;
    }

    public function toArray(): array
    {
        $data = [];
        if (!empty($this->start)) {
            $data['start'] = (new DateTime($this->start))
                ->setTimezone(new DateTimeZone($this->timezone))
                ->format('c');
        }

        if (!empty($this->end)) {
            $data['end'] = (new DateTime($this->end))
                ->setTimezone(new DateTimeZone($this->timezone))
                ->format('c');
        }

        return $data;
    }
}

<?php
namespace agumil\SatuSehatSDK\Helper;

use DateTime;
use DateTimeZone;

class DateHelper
{
    private DateTime $date;

    public function __construct(string | int $date, string $timezone = 'Asia/Jakarta')
    {
        if (is_string($date) && !is_numeric($date)) {
            $this->date = (new DateTime($date))->setTimezone(new DateTimeZone($timezone));
        } else {
            $this->date = (new DateTime())->setTimestamp($date)->setTimezone(new DateTimeZone($timezone));
        }
    }

    public function dateTimeISO8601()
    {
        return $this->date->format('c');
    }

    public function dateISO8601()
    {
        return $this->date->format('Y-m-d');
    }

    public function timeISO8601()
    {
        return $this->date->format('H:i:s');
    }
}

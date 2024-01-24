<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use DateTime;
use DateTimeZone;

class ExtensionTransportedTime
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime';

    private string $dateTime;

    private string $timezone;

    public function __construct(string $dateTime, string $tz = 'Asia/Jakarta')
    {
        $tmp = strtotime($dateTime);
        if ($tmp === false) {
            throw new SSDataTypeException('Parameter dateTime is unparseable by strtotime. Please provide a valid date.');
        }

        $this->dateTime = $dateTime;
        $this->timezone = $tz;
    }

    public function toArray(): array
    {
        $dateTime = (new DateTime($this->dateTime))
            ->setTimezone(new DateTimeZone($this->timezone))
            ->format('c');

        return (new Extension(self::$url, $dateTime, 'DateTime'))->toArray();
    }
}

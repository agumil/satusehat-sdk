<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\Timing;
use agumil\SatuSehatSDK\Terminology\HL7\DaysOfWeek;
use agumil\SatuSehatSDK\Terminology\HL7\UnitsOfTime;

// Every 8 hours
$timing1 = (new Timing())->setFrequency(1)
    ->setPeriod(8)
    ->setPeriodUnit(UnitsOfTime::CODE_HOUR);
$result1 = $timing1->toArray();

// Three times a week for 1/2 hour
$timing2 = (new Timing())->setDuration(0.5)
    ->setDurationUnit(UnitsOfTime::CODE_HOUR)
    ->setFrequency(3)
    ->setPeriod(1)
    ->setPeriodUnit(UnitsOfTime::CODE_WEEK);
$result2 = $timing2->toArray();

// Mon, Wed, Fri, Morning
$timing3 = (new Timing())->setFrequency(1)
    ->setPeriod(1)
    ->setPeriodUnit(UnitsOfTime::CODE_DAY)
    ->setDayOfWeek(
        DaysOfWeek::CODE_MONDAY,
        DaysOfWeek::CODE_WEDNESDAY,
        DaysOfWeek::CODE_FRIDAY
    );
$result3 = $timing3->toArray();

var_dump($result1, $result2, $result3);

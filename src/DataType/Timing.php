<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\HL7\DaysOfWeek;
use agumil\SatuSehatSDK\Terminology\HL7\EventTimingV2;
use agumil\SatuSehatSDK\Terminology\HL7\EventTimingV5;
use agumil\SatuSehatSDK\Terminology\HL7\TimingAbbreviationV2;

class Timing
{
    private ?array $event;

    private ?array $code;

    private ?int $count;

    private ?int $count_max;

    private float|int|null $duration;

    private float|int|null $duration_max;

    private ?string $duration_unit;

    private ?int $frequency;

    private ?int $frequency_max;

    private float|int|null $period;

    private float|int|null $period_max;

    private ?string $period_unit;

    private ?array $day_of_week;

    private ?array $time_of_day;

    private ?array $when;

    private ?int $offset;

    private Duration|Range|Period|null $bounds;

    public function __construct(?array $event = null, ?array $code = null, ?int $count = null, ?int $countMax = null, float | int $duration = null, float | int $durationMax = null, ?string $durationUnit = null, ?int $frequency = null, ?int $frequencyMax = null, float | int $period = null, float | int $periodMax = null, ?string $periodUnit = null, ?array $dayOfWeek = null, ?array $timeOfDay = null, ?array $when = null, ?int $offset = null, Duration | Range | Period $bounds = null)
    {
        $this->event = $event;
        $this->code = $code;
        $this->count = $count;
        $this->count_max = $countMax;
        $this->duration = $duration;
        $this->duration_max = $durationMax;
        $this->duration_unit = $durationUnit;
        $this->frequency = $frequency;
        $this->frequency_max = $frequencyMax;
        $this->period = $period;
        $this->period_max = $periodMax;
        $this->period_unit = $periodUnit;
        $this->day_of_week = $dayOfWeek;
        $this->time_of_day = $timeOfDay;
        $this->when = $when;
        $this->offset = $offset;
        $this->bounds = $bounds;
    }

    private function _validateProperties()
    {
        if (!empty($this->event)) {
            foreach ($this->event as $k => $e) {
                $this->event[$k] = ValidatorHelper::dateTime($e);
            }
        }

        if (!empty($this->code)) {
            $tmpcoding = [];
            foreach ($this->code as $k => $c) {
                ValidatorHelper::in("code[{$k}]", $c, TimingAbbreviationV2::getCodes());
                $tmpcoding[] = new Coding(TimingAbbreviationV2::SYSTEM, $c, TimingAbbreviationV2::getDisplayCode($c));
            }

            $this->code = (new CodeableConcept(null, ...$tmpcoding))->toArray();
        }

        if (isset($this->count_max) && !isset($this->count)) {
            throw new SSDataTypeException('Parameter count is required if parameter countMax provided.');
        }

        if (isset($this->count)) {
            ValidatorHelper::positiveInt('count', $this->count);
        }

        if (isset($this->count_max)) {
            ValidatorHelper::positiveInt('countMax', $this->count_max);
        }

        if (isset($this->duration)) {
            ValidatorHelper::decimal('duration', $this->duration);

            if ($this->duration < 0) {
                throw new SSDataTypeException('Parameter duration must be a non-negative value.');
            }
        }

        if (isset($this->duration_max)) {
            ValidatorHelper::decimal('durationMax', $this->duration_max);

            if ($this->duration_max < 0) {
                throw new SSDataTypeException('Parameter durationMax must be a non-negative value.');
            }
        }

        if (isset($this->frequency)) {
            ValidatorHelper::positiveInt('frequency', $this->frequency);
        }

        if (isset($this->frequency_max)) {
            ValidatorHelper::positiveInt('frequencyMax', $this->frequency_max);
        }

        if (isset($this->period)) {
            ValidatorHelper::decimal('period', $this->period);

            if ($this->period < 0) {
                throw new SSDataTypeException('Parameter period must be a non-negative value.');
            }
        }

        if (isset($this->period_max)) {
            ValidatorHelper::decimal('periodMax', $this->period_max);

            if ($this->period_max < 0) {
                throw new SSDataTypeException('Parameter periodMax must be a non-negative value.');
            }
        }

        if (!empty($this->day_of_week)) {
            foreach ($this->day_of_week as $k => $dow) {
                ValidatorHelper::in("dayOfWeek[{$k}]", $dow, DaysOfWeek::getCodes());
            }
        }

        if (!empty($this->time_of_day)) {
            foreach ($this->time_of_day as $k => $tod) {
                $this->time_of_day = ValidatorHelper::time($tod);
            }
        }

        if (isset($this->duration_max) && !isset($this->duration)) {
            throw new SSDataTypeException('Parameter duration is required if parameter durationMax provided.');
        }

        if (isset($this->duration) && empty($this->duration_unit)) {
            throw new SSDataTypeException('Parameter durationUnit is required if parameter duration provided.');
        }

        if (!empty($this->duration_unit)) {
            ValidatorHelper::in('durationUnit', $this->duration_unit, ['s', 'min', 'h', 'd', 'wk', 'mo', 'a']);
        }

        if (isset($this->period) && ($this->period < 0)) {
            throw new SSDataTypeException('Parameter period must be a non-negative value.');
        }

        if (isset($this->period_max) && !isset($this->period)) {
            throw new SSDataTypeException('Parameter period is required if parameter periodMax provided.');
        }

        if (isset($this->period) && empty($this->period_unit)) {
            throw new SSDataTypeException('Parameter periodUnit is required if parameter period provided.');
        }

        if (!empty($this->period_unit)) {
            ValidatorHelper::in('periodUnit', $this->period_unit, ['s', 'min', 'h', 'd', 'wk', 'mo', 'a']);
        }

        if (!empty($this->when)) {
            foreach ($this->when as $k => $w) {
                ValidatorHelper::in("when[{$k}]", $w, array_merge(EventTimingV2::getCodes(), EventTimingV5::getCodes()));
            }
        }

        if (isset($this->offset)) {
            ValidatorHelper::unsignedInt('offset', $this->offset);

            if (empty($this->when)) {
                throw new SSDataTypeException('Parameter when is required if parameter offset provided.');
            }

            foreach ($this->when as $k => $w) {
                if (in_array($w, [EventTimingV2::CODE_C, EventTimingV2::CODE_CD, EventTimingV2::CODE_CM, EventTimingV2::CODE_CV])) {
                    throw new SSDataTypeException("Value {$w} for parameter when is not allowed if parameter offset provided.");
                }
            }
        }

        if (!empty($this->when) && !empty($this->time_of_day)) {
            throw new SSDataTypeException('Parameter timeOfDay and parameter when is not allowed to be exist at the same time.');
        }
    }

    public function setEvent(string ...$dateTime)
    {
        $this->event = $dateTime;

        return $this;
    }

    public function setCode(string ...$code)
    {
        $this->code = $code;

        return $this;
    }

    public function setCount(int $count)
    {
        $this->count = $count;

        return $this;
    }

    public function setCountMax(int $countMax)
    {
        $this->count_max = $countMax;

        return $this;
    }

    public function setDuration(float | int $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function setDurationMax(float | int $durationMax)
    {
        $this->duration_max = $durationMax;

        return $this;
    }

    public function setDurationUnit(string $durationUnit)
    {
        $this->duration_unit = $durationUnit;

        return $this;
    }

    public function setFrequency(int $frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function setFrequencyMax(int $frequencyMax)
    {
        $this->frequency_max = $frequencyMax;

        return $this;
    }

    public function setPeriod(float | int $period)
    {
        $this->period = $period;

        return $this;
    }

    public function setPeriodMax(float | int $periodMax)
    {
        $this->period_max = $periodMax;

        return $this;
    }

    public function setPeriodUnit(string $periodUnit)
    {
        $this->period_unit = $periodUnit;

        return $this;
    }

    public function setDayOfWeek(string ...$day)
    {
        $this->day_of_week = $day;

        return $this;
    }

    public function setTimeOfDay(string ...$time)
    {
        $this->time_of_day = $time;

        return $this;
    }

    public function setWhen(string...$when)
    {
        $this->when = $when;

        return $this;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setBounds(Duration | Range | Period $bounds)
    {
        $this->bounds = $bounds;

        return $this;
    }

    public function toArray(): array
    {
        $this->_validateProperties();
        $data = [];

        if (!empty($this->event)) {
            $data['event'] = $this->event;
        }

        if (!empty($this->code)) {
            $data['code'] = $this->code;
        }

        if (isset($this->count)) {
            $data['repeat']['count'] = $this->count;
        }

        if (isset($this->count_max)) {
            $data['repeat']['countMax'] = $this->count_max;
        }

        if (isset($this->duration)) {
            $data['repeat']['duration'] = $this->duration;
        }

        if (isset($this->duration_max)) {
            $data['repeat']['durationMax'] = $this->duration_max;
        }

        if (!empty($this->duration_unit)) {
            $data['repeat']['durationUnit'] = $this->duration_unit;
        }

        if (isset($this->frequency)) {
            $data['repeat']['frequency'] = $this->frequency;
        }

        if (isset($this->frequency_max)) {
            $data['repeat']['frequencyMax'] = $this->frequency_max;
        }

        if (isset($this->period)) {
            $data['repeat']['period'] = $this->period;
        }

        if (isset($this->period_max)) {
            $data['repeat']['periodMax'] = $this->period_max;
        }

        if (!empty($this->period_unit)) {
            $data['repeat']['periodUnit'] = $this->period_unit;
        }

        if (!empty($this->day_of_week)) {
            $data['repeat']['dayOfWeek'] = $this->day_of_week;
        }

        if (!empty($this->time_of_day)) {
            $data['repeat']['timeOfDay'] = $this->time_of_day;
        }

        if (!empty($this->when)) {
            $data['repeat']['when'] = $this->when;
        }

        if (isset($this->offset)) {
            $data['repeat']['offset'] = $this->offset;
        }

        if (isset($this->bounds)) {
            if ($this->bounds instanceof Duration) {
                $data['repeat']['boundsDuration'] = $this->bounds->toArray();
            } elseif ($this->bounds instanceof Range) {
                $data['repeat']['boundsRange'] = $this->bounds->toArray();
            } elseif ($this->bounds instanceof Period) {
                $data['repeat']['boundsPeriod'] = $this->bounds->toArray();
            }
        }

        return $data;
    }
}

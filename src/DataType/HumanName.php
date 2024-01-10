<?php
namespace agumil\SatuSehatSDK\DataType;

class HumanName
{
    private string $use;

    private string $text;

    private array $given;

    private ?string $family;

    private ?string $prefix;

    private ?string $suffix;

    private ?Period $period;

    public function __construct(string $use, array $given, ?string $family = null, ?string $prefix = null, ?string $suffix = null, ?Period $period = null)
    {
        $this->use = $use;
        $this->given = $given;
        $this->family = $family;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->period = $period;

        $this->text = implode(' ', $given);
        if (!empty($prefix)) {
            $this->text = $prefix . ' ' . $this->text;
        }

        if (!empty($family)) {
            $this->text = $this->text . ' ' . $family;
        }

        if (!empty($suffix)) {
            $this->text = $this->text . ' ' . $suffix;
        }
    }

    public function toArray(): array
    {
        $data = [
            'use' => $this->use,
            'text' => $this->text,
            'given' => $this->given,
        ];

        if (!empty($this->family)) {
            $data['family'] = $this->family;
        }

        if (!empty($this->prefix)) {
            $data['prefix'] = $this->prefix;
        }

        if (!empty($this->suffix)) {
            $data['suffix'] = $this->suffix;
        }

        if (isset($this->period)) {
            $data['period'] = $this->period->toArray();
        }

        return $data;
    }
}

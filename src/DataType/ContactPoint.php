<?php
namespace agumil\SatuSehatSDK\DataType;

class ContactPoint
{
    private string $use;

    private string $system;

    private string $value;

    private ?int $rank;

    private ?Period $period;

    public function __construct(string $use, string $system, string $value, ?int $rank = null, ?Period $period = null)
    {
        $this->use = $use;
        $this->system = $system;
        $this->value = $value;
        $this->rank = $rank;
        $this->period = $period;
    }

    public function toArray(): array
    {
        $data = [
            'use' => $this->use,
            'system' => $this->system,
            'value' => $this->value,
        ];

        if (!empty($this->rank)) {
            $data['rank'] = $this->rank;
        }

        if (!empty($this->period)) {
            $data['period'] = $this->period;
        }

        return $data;
    }
}

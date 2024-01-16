<?php
namespace agumil\SatuSehatSDK\DataType;

class ContactPoint
{
    private string $use;

    private string $system;

    private ?string $value;

    private ?int $rank;

    private ?Period $period;

    public function __construct(string $system, string $use, ?string $value = null, ?int $rank = null, ?Period $period = null)
    {
        $this->use = $use;
        $this->system = $system;
        $this->value = $value;
        $this->rank = $rank;
        $this->period = $period;
    }

    public function toArray(): array
    {
        if (isset($this->system)) {
            $data['system'] = $this->system;
        }

        if (isset($this->use)) {
            $data['use'] = $this->use;
        }

        if (isset($this->value)) {
            $data['value'] = $this->value;
        }

        if (isset($this->rank)) {
            $data['rank'] = $this->rank;
        }

        if (isset($this->period)) {
            $data['period'] = $this->period->toArray();
        }

        return $data;
    }
}

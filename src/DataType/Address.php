<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;
use agumil\SatuSehatSDK\Terminology\HL7\AddressType;
use agumil\SatuSehatSDK\Terminology\HL7\AddressUse;

class Address
{
    private string $text;

    private string $use;

    private string $type;

    private array $lines;

    private ?string $district;

    private ?string $city;

    private ?string $state;

    private ?string $postal_code;

    private ?string $country;

    private ?Period $period;

    public function __construct(string $use, string $type, array $lines, ?string $district = null, ?string $city = null, ?string $state = null, ?string $postalCode = null, ?string $country = null, ?Period $period = null)
    {
        ValidatorHelper::in('use', $use, AddressUse::getCodes());
        ValidatorHelper::in('type', $type, AddressType::getCodes());

        $this->use = $use;
        $this->type = $type;
        $this->lines = $lines;
        $this->district = trim($district);
        $this->city = trim($city);
        $this->state = trim($state);
        $this->postal_code = trim($postalCode);
        $this->country = trim(strtoupper($country));
        $this->period = $period;

        $this->text = implode(', ', $lines);

        if (!empty($district)) {
            $this->text = $this->text . ', ' . $district;
        }

        if (!empty($city)) {
            $this->text = $this->text . ', ' . $city;
        }

        if (!empty($state)) {
            $this->text = $this->text . ', ' . $state;
        }

        if (!empty($postalCode)) {
            $this->text = $this->text . ', ' . $postalCode;
        }
    }

    public function toArray(): array
    {
        $data = [
            'use' => $this->use,
            'type' => $this->type,
            'line' => $this->lines,
        ];

        if (!empty($this->district)) {
            $data['district'] = $this->district;
        }

        if (!empty($this->city)) {
            $data['city'] = $this->city;
        }

        if (!empty($this->state)) {
            $data['state'] = $this->state;
        }

        if (!empty($this->postal_code)) {
            $data['postalCode'] = $this->postal_code;
        }

        if (!empty($this->country)) {
            $data['country'] = $this->country;
        }

        if (!empty($this->text)) {
            $data['text'] = $this->text;
        }

        if (!empty($this->period)) {
            $data['period'] = $this->period->toArray();
        }

        return $data;
    }
}

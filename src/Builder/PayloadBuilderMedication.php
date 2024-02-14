<?php
namespace agumil\SatuSehatSDK\Builder;

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\ExtensionMedicationType;
use agumil\SatuSehatSDK\DataType\Identifier;
use agumil\SatuSehatSDK\DataType\Ratio;
use agumil\SatuSehatSDK\DataType\Reference;
use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class PayloadBuilderMedication
{
    private static $resource_type = 'Medication';

    private array $identifier;

    private array $code;

    private string $status;

    private array $manufacturer;

    private array $form;

    private array $amount;

    private array $ingredient;

    private array $batch;

    private array $extension;

    public function addIdentifier(Identifier $identifier)
    {
        $this->identifier[] = $identifier->toArray();

        return $this;
    }

    public function setCode(CodeableConcept $code)
    {
        $this->code = $code->toArray();

        return $this;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function setManufacturer(Reference $manufacturer)
    {
        $this->manufacturer = $manufacturer->toArray();

        return $this;
    }

    public function setForm(CodeableConcept $form)
    {
        $this->form = $form->toArray();

        return $this;
    }

    public function setAmount(Ratio $amount)
    {
        $this->amount = $amount->toArray();

        return $this;
    }

    public function addIngredient(CodeableConcept $item, bool $isActive, Ratio $strength)
    {
        $this->ingredient[] = [
            'itemCodeableConcept' => $item->toArray(),
            'isActive' => $isActive,
            'strength' => $strength->toArray(),
        ];

        return $this;
    }

    public function setBatch(string $lotNumber, string $expirationDate)
    {
        $expirationDate = ValidatorHelper::dateTime('dateTime', $expirationDate);

        $this->batch = [
            'lotNumber' => $lotNumber,
            'expirationDate' => $expirationDate,
        ];

        return $this;
    }

    public function addMedicationType(ExtensionMedicationType $medicationType)
    {
        $this->extension[] = $medicationType->toArray();

        return $this;
    }

    public function build(): array
    {
        $data['resourceType'] = self::$resource_type;

        if (!empty($this->identifier)) {
            $data['identifier'] = $this->identifier;
        }

        if (!empty($this->code)) {
            $data['code'] = $this->code;
        }

        if (!empty($this->status)) {
            $data['status'] = $this->status;
        }

        if (!empty($this->manufacturer)) {
            $data['manufacturer'] = $this->manufacturer;
        }

        if (!empty($this->form)) {
            $data['form'] = $this->form;
        }

        if (!empty($this->amount)) {
            $data['amount'] = $this->amount;
        }

        if (!empty($this->ingredient)) {
            $data['ingredient'] = $this->ingredient;
        }

        if (!empty($this->batch)) {
            $data['batch'] = $this->batch;
        }

        if (!empty($this->extension)) {
            $data['extension'] = $this->extension;
        }

        return $data;
    }

    public function buildAsJSON(): string
    {
        return json_encode($this->build());
    }
}

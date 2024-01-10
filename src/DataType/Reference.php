<?php
namespace agumil\SatuSehatSDK\DataType;

class Reference
{
    private string $reference;

    private ?string $type;

    private ?string $display;

    private ?Identifier $identifier;

    public function __construct(string $reference, ?string $type = null, ?string $display = null, ?Identifier $identifier = null)
    {
        $this->reference = $reference;
        $this->type = $type;
        $this->display = $display;
        $this->identifier = $identifier;
    }

    public function toArray(): array
    {
        $data['reference'] = $this->reference;

        if (!empty($this->type)) {
            $data['type'] = $this->type;
        }

        if (!empty($this->display)) {
            $data['display'] = $this->display;
        }

        if (isset($this->identifier)) {
            $data['identifier'] = $this->identifier->toArray();
        }

        return $data;
    }
}

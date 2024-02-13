<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class Narrative
{
    private string $status;

    private string $div;

    public function __construct(string $status, string $div)
    {
        ValidatorHelper::in('status', $status, ['generated', 'extensions', 'additional', 'empty']);

        $this->status = $status;
        $this->div = $div;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'div' => $this->div,
        ];
    }
}

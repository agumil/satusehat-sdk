<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\DataType\Coding;

class CodingMulti
{
    private array $codings;

    public function __construct(Coding ...$coding)
    {
        $this->codings = $coding;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->codings as $coding) {
            $data[] = $coding->toArray();
        }

        return $data;
    }
}

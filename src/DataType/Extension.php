<?php
namespace agumil\SatuSehatSDK\DataType;

class Extension
{
    private string $url;

    private mixed $value;

    private string $x;

    public function __construct(string $url, mixed $value, string $x)
    {
        $this->url = $url;
        $this->value = $value;
        $this->x = $x;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            "value{$this->x}" => $this->value,
        ];
    }
}

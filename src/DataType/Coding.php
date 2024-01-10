<?php
namespace agumil\SatuSehatSDK\DataType;

class Coding
{
    private string $system;

    private string $code;

    private string $display;

    private ?bool $userSelected;

    private ?string $version;

    public function __construct(string $system, string $code, string $display, ?bool $userSelected = null, ?string $version = null)
    {
        $this->system = $system;
        $this->code = $code;
        $this->display = $display;
        $this->userSelected = $userSelected;
        $this->version = $version;
    }

    public function toArray(): array
    {
        $data = [
            'system' => $this->system,
            'code' => $this->code,
            'display' => $this->display,
        ];

        if (!empty($this->userSelected)) {
            $data['userSelected'] = $this->userSelected;
        }

        if (!empty($this->version)) {
            $data['version'] = $this->version;
        }

        return $data;
    }
}

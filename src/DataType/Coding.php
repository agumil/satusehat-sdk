<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Exception\SSDataTypeException;

class Coding
{
    private ?string $system;

    private ?string $code;

    private ?string $display;

    private ?bool $userSelected;

    private ?string $version;

    public function __construct(?string $system = null, ?string $code = null, ?string $display = null, ?bool $userSelected = null, ?string $version = null)
    {
        if (empty($code) && !empty($display)) {
            throw new SSDataTypeException('Parameter code must be provice when parameter display is exist.');
        }

        $this->system = $system;
        $this->code = $code;
        $this->display = $display;
        $this->userSelected = $userSelected;
        $this->version = $version;
    }

    public function toArray(): array
    {
        if (isset($this->system)) {
            $data['system'] = $this->system;
        }

        if (isset($this->code)) {
            $data['code'] = $this->code;
        }

        if (isset($this->display)) {
            $data['display'] = $this->display;
        }

        if (isset($this->userSelected)) {
            $data['userSelected'] = $this->userSelected;
        }

        if (isset($this->version)) {
            $data['version'] = $this->version;
        }

        return $data;
    }
}

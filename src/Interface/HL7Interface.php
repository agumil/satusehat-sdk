<?php
namespace agumil\SatuSehatSDK\Interface;

interface HL7Interface
{
    public static function getCodes(): array;
    public static function getDisplayCode(string $code): null | string;
}

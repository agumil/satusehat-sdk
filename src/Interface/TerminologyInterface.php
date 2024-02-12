<?php
namespace agumil\SatuSehatSDK\Interface;

interface TerminologyInterface
{
    public static function getCodes(): array;
    public static function getDisplayCode(string $code): null | string;
}

<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\ExtensionLocationServiceClass;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;

$class = ServiceClassOutpatient::CODE_KELAS_REGULER;
$var = (new ExtensionLocationServiceClass($class))->toArray();

var_dump($var);

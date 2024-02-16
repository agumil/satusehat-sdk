<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\ExtensionServiceClass;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassUpgrade;

$class = ServiceClassOutpatient::CODE_KELAS_REGULER;
$upgradeClass = ServiceClassUpgrade::CODE_KELAS_NAIK;
$var = (new ExtensionServiceClass($class, $upgradeClass))->toArray();

var_dump($var);

<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\ExtensionAdministrativeCode;

// extension exists
$var = new ExtensionAdministrativeCode(33, 3301, 330101, 33010101);
$var = $var->toArray();

// extension not exists
$var2 = new ExtensionAdministrativeCode();
$var2 = $var2->toArray();

var_dump($var, $var2);

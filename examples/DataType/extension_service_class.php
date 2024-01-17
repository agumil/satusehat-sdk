<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\ExtensionServiceClass;
use agumil\SatuSehatSDK\Terminology\KemKes\ServiceClassOutpatient;

// extension exists
$bpjs_class = ServiceClassOutpatient::CODE_KELAS_REGULER;
$bpjs_class_display_name = ServiceClassOutpatient::getDisplayCode($bpjs_class);
$coding1 = new Coding(
    ServiceClassOutpatient::SYSTEM,
    $bpjs_class,
    $bpjs_class_display_name
);

$value = new CodeableConcept(null, $coding1);
$var = new ExtensionServiceClass($value);
$var = $var->toArray();

// extension not exists
$var2 = new ExtensionServiceClass();
$var2 = $var2->toArray();

var_dump($var, $var2);

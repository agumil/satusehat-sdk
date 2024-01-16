<?php require __DIR__ . '/../bootstrap.php';

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;

$coding1 = new Coding('test', 'code', 'Code');
$coding2 = new Coding('test2', 'code2', 'Code 2');

$codeableConcept = new CodeableConcept(null, $coding1, $coding2);

var_dump($codeableConcept->toArray());

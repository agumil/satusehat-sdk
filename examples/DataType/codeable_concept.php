<?php require __DIR__ . '/../../bootstrap.php';

use agumil\SatuSehatSDK\DataType\CodeableConcept;
use agumil\SatuSehatSDK\DataType\Coding;
use agumil\SatuSehatSDK\DataType\CodingMulti;

$coding1 = new Coding('test', 'code', 'Code');
$coding2 = new Coding('test2', 'code2', 'Code 2');

$codingMulti = new CodingMulti($coding1, $coding2);

$codeableConcept = new CodeableConcept($codingMulti);

var_dump($codeableConcept->toArray());

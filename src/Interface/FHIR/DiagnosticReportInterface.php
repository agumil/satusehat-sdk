<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface DiagnosticReportInterface
{
    public function getDiagnosticReport(array $params = []);
    public function getDiagnosticReportById(string $id);
    public function createDiagnosticReport($params);
    public function updateDiagnosticReport(string $id, $params);
}

<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface AppointmentResponseInterface
{
    public function getAppointmentResponse(array $params = []);
    public function getAppointmentResponseById(string $id);
    public function createAppointmentResponse($params);
    public function updateAppointmentResponse(string $id, $params);
}

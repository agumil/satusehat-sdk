<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface AppointmentInterface
{
    public function getAppointment(array $params = []);
    public function getAppointmentById(string $id);
    public function createAppointment($params);
    public function updateAppointment(string $id, $params);
}

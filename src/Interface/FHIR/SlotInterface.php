<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface SlotInterface
{
    public function getSlot(array $params = []);
    public function getSlotById(string $id);
    public function createSlot($params);
    public function updateSlot(string $id, $params);
}

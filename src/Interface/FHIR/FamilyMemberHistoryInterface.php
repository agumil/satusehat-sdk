<?php
namespace agumil\SatuSehatSDK\Interface\FHIR;

interface FamilyMemberHistoryInterface
{
    public function getFamilyMemberHistory(array $params = []);
    public function getFamilyMemberHistoryById(string $id);
    public function createFamilyMemberHistory($params);
    public function updateFamilyMemberHistory(string $id, $params);
}

<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class ExtensionCitizenshipStatus
{
    private static $url = 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus';

    private string $status;

    public function __construct(string $citizenshipStatus)
    {
        ValidatorHelper::in('citizenshipStatus', $citizenshipStatus, ['WNI', 'WNA']);

        $this->status = $citizenshipStatus;
    }

    public function toArray(): array
    {
        return (new Extension(self::$url, $this->status, 'Code'))->toArray();
    }
}

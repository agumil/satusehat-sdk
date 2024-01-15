<?php
namespace agumil\SatuSehatSDK\DataType;

class ExtensionAdministrativeCode
{
    private ?int $province;

    private ?int $city;

    private ?int $district;

    private ?int $village;

    private ?int $rt;

    private ?int $rw;

    public function __construct(?int $province = null, ?int $city = null, ?int $district = null, ?int $village = null, ?int $rt = null, ?int $rw = null)
    {
        $this->province = $province;
        $this->city = $city;
        $this->district = $district;
        $this->village = $village;
        $this->rt = $rt;
        $this->rw = $rw;
    }

    public function toArray(): array
    {
        $extensions = [];

        if (isset($this->province)) {
            $extensions[] = new Extension('province', (string) $this->province, 'Code');
        }

        if (isset($this->city)) {
            $extensions[] = new Extension('city', (string) $this->city, 'Code');
        }

        if (isset($this->district)) {
            $extensions[] = new Extension('district', (string) $this->district, 'Code');
        }

        if (isset($this->village)) {
            $extensions[] = new Extension('village', (string) $this->village, 'Code');
        }

        if (isset($this->rt)) {
            $extensions[] = new Extension('rt', (string) $this->rt, 'Code');
        }

        if (isset($this->rw)) {
            $extensions[] = new Extension('rw', (string) $this->rw, 'Code');
        }

        $extensionExtended = new ExtensionExtended(
            'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
            ...$extensions
        );

        return $extensionExtended->toArray();
    }
}

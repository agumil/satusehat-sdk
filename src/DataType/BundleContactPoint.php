<?php
namespace agumil\SatuSehatSDK\DataType;

class BundleContactPoint
{
    private array $contact_points;

    public function __construct(ContactPoint ...$contactPoint)
    {
        $this->contact_points = $contactPoint;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->contact_points as $contactPoint) {
            $data[] = $contactPoint->toArray();
        }

        return $data;
    }
}

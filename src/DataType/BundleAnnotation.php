<?php
namespace agumil\SatuSehatSDK\DataType;

class BundleAnnotation
{
    private array $annotations;

    public function __construct(Annotation ...$annotation)
    {
        $this->annotations = $annotation;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->annotations as $annotation) {
            $data[] = $annotation->toArray();
        }

        return $data;
    }
}

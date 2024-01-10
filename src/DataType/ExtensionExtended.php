<?php
namespace agumil\SatuSehatSDK\DataType;

class ExtensionExtended
{
    private string $url;

    private array $extensions;

    public function __construct(string $url, Extension ...$extensions)
    {
        $this->url = $url;
        $this->extensions = $extensions;
    }

    public function toArray(): array
    {
        $data['url'] = $this->url;
        foreach ($this->extensions as $ext) {
            $data['extension'][] = $ext->toArray();
        }

        return $data;
    }
}

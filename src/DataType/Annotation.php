<?php
namespace agumil\SatuSehatSDK\DataType;

use agumil\SatuSehatSDK\Helper\ValidatorHelper;

class Annotation
{
    private string $text;

    private ?string $time;

    private ?array $author;

    public function __construct(string $text, ?string $dateTime = null, Reference | string $author = null)
    {
        if (!empty($dateTime)) {
            $dateTime = ValidatorHelper::dateTime('dateTime', $dateTime);
        }

        if (!empty($author)) {
            if ($author instanceof Reference) {
                $this->author = ['authorReference' => $author->toArray()];
            } else {
                $this->author = ['authorString' => (string) $author];
            }

        }

        $this->text = $text;
        $this->time = $dateTime;
    }

    public function toArray(): array
    {
        $data['text'] = $this->text;

        if (isset($this->time)) {
            $data['time'] = $this->time;
        }

        if (isset($this->author)) {
            $data = array_merge($data, $this->author);
        }

        return $data;
    }
}

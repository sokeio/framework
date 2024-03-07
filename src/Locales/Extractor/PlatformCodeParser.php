<?php

namespace Sokeio\Locales\Extractor;

class PlatformCodeParser
{
    public static function extractTranslation($type,  $files,  $name = '')
    {
        return (new self($type, $files, $name))->extract();
    }
    public function __construct(private $type, private $files, private $name = '')
    {
    }
    public function extract()
    {
        $strings = [];
        foreach ($this->files as $file) {
            $strings = array_merge($strings, CodeParser::inst()->parse($file));
        }

        return [
            'type' => $this->type,
            'name' => $this->name,
            'translation' => $this->formatArray($strings)
        ];
    }
    protected function formatArray(array $strings)
    {
        $result = [];

        foreach ($strings as $string) {
            $result[$string] = $string;
        }

        return $result;
    }
}

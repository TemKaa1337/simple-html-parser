<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser;

class AttributeParser
{
    public function __construct(private readonly string $tag) {}

    public function parse(): array
    {
        if (strpos($this->tag, ' ') === false) return [];

        $result = [];
        $tagInfo = explode(' ', $this->tag);
        array_shift($tagInfo);

        foreach ($tagInfo as $attribute) {
            if (strpos($attribute, '=') === false) {
                $name = trim($attribute);
                $value = null;
            } else {
                [$name, $value] = array_map('trim', explode('=', $attribute));
                $value = str_replace('"', '', $value);
            }

            $result[] = new Attribute(
                name: $name, 
                value: $value
            );
        }

        return $result;
    }
}
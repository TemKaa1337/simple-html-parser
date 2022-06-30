<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser;

class Node
{
    public function __construct(
        private readonly string $raw, 
        private readonly array $children,
        private readonly array $attributes,
        private readonly string $tag
    )
    {
        $trimmed = trim($raw);
        // var_dump($raw);
    }
}
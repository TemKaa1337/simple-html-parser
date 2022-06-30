<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser;

use TemKaa1337\SimpleHtmlParser\Contracts\DomInteraction;

class Dom implements DomInteraction
{
    public function __construct(private array $nodes) {}

    public function findByClassName(string $class, int $index): void
    {

    }

    public function findByClassNames(array $class, int $index): void
    {
        
    }

    public function findByTagName(string $tag, int $index): void
    {

    }

    public function findByAttribute(string $attribute, mixed $value): void
    {
        
    }

    public function findById(string $id): void
    {

    }
}
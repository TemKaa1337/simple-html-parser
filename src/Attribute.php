<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser;

class Attribute
{
    public function __construct(
        private readonly string $name,
        private readonly mixed $value
    )
    { }

    public function is(string $attribute): bool
    {
        return $this->name === $attribute;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
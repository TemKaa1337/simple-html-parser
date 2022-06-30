<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser\Input;

use TemKaa1337\SimpleHtmlParser\Contracts\Input;

class StringInput implements Input
{
    public function __construct(private readonly string $s) {}

    public function contents(): string
    {
        if (empty($this->s)) {
            throw new \InvalidArgumentException("The provided string is empty.");
        }

        return $this->s;
    }
}
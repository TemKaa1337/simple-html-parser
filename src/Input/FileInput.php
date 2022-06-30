<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser\Input;

use TemKaa1337\SimpleHtmlParser\Contracts\Input;

class FileInput implements Input
{
    public function __construct(private readonly string $path) {}

    public function contents(): string
    {
        if (!file_exists($this->path)) {
            throw new \InvalidArgumentException("File doesn't exist at specified path.");
        }

        $fileContents = file_get_contents($this->path);
        if ($fileContents === false) {
            throw new \InvalidArgumentException("Cannot retrieve contents of the fiven file.");
        }

        return $fileContents;
    }
}
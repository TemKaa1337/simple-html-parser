<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser\Input;

use TemKaa1337\SimpleHtmlParser\Contracts\Input;

class URLInput implements Input
{
    public function __construct(
        private readonly string $url,
        private readonly array $options = []
    ) {}

    public function contents(): string
    {
        $encodedUrl = urlencode($this->url);
        $options = stream_context_create($this->options);

        $response = file_get_contents(filename: $encodedUrl, context: $options);
        if ($response === false) {
            throw new \InvalidArgumentException("Cannot retrieve contents of an url.");
        }

        return $response;
    }
}
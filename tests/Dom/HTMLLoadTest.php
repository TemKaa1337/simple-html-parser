<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TemKaa1337\SimpleHtmlParser\Input\StringInput;
use TemKaa1337\SimpleHtmlParser\Parser;

final class HTMLLoadTest extends TestCase
{
    private const TEST_HTML = <<<EOT
        <!DOCTYPE html>
        <html>
            <head>
                <title>Page Title</title>
            </head>
            <body>
                <h1>This is a Heading</h1>
                <p>This is a paragraph.</p>
            </body>
        </html>
    EOT;

    public function testHtmlLoadAsFileWhenFileExists(): void
    {
        
    }

    public function testHtmlLoadAsFileWhenFileDoesntExist(): void
    {
        
    }

    public function testHtmlLoadAsStringWhenStringIsEmpty(): void
    {

    }

    public function testHtmlLoadAsStringWhenStringIsNonEmpty(): void
    {
        $parser = new Parser(
            input: new StringInput(s: self::TEST_HTML)
        );
        $dom = $parser->parse();
        // $prettifiedOutput = preg_replace('/\s+/', '', (string) $dom);

        $this->assertEquals((string) $dom, self::TEST_HTML);
    }

    public function testHtmlLoadAsUrlWhenUrlIsInvalid(): void
    {

    }

    public function testHtmlLoadAsUrlWhenUrlIsValid(): void
    {

    }
}
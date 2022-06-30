<?php declare(strict_types = 1);

namespace TemKaa1337\SimpleHtmlParser;

use TemKaa1337\SimpleHtmlParser\Contracts\Input;

class Parser
{
	public const BLANK_TOKEN = ' \t\r\n';

    private readonly string $input;
    private readonly string $formattedInput;
    
	private const SELF_CLOSING_TAGS = [
		'area', 'base', 'br', 'col', 'embed',
		'hr', 'img', 'input', 'link', 'meta',
		'param', 'source', 'track', 'wbr',
        '!doctype'
	];

    public function __construct(Input $input) 
    {
        $this->input = $input->contents();
    }

    public function parse(): int
    {
        if (substr_count($this->input, '<') !== substr_count($this->input, '>')) {
            throw new \InvalidArgumentException('Invalid HTML provided.');
        }

        $this->formattedInput = $this->removeNoise(
            $this->input, 
            [
                "'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is",
                "'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is",
                "'<!\[CDATA\[(.*?)\]\]>'is",
                "'<!--(.*?)-->'is",
                "'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is",
                "'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is",
                "'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is",
                "'(<\?)(.*?)(\?>)'s"
            ], 
            replaceWith: ''
        );
        // var_dump($this->formattedInput);
        
        $nodes = $this->parseNode(s: $this->formattedInput);

        // var_dump($nodes);
        return 1;
    }

    private function removeNoise(string $s, array $patterns, string $replaceWith): string
	{
        foreach ($patterns as $pattern) {
            $count = preg_match_all(
                $pattern,
                $s,
                $matches,
                PREG_SET_ORDER | PREG_OFFSET_CAPTURE
            );
    
            for ($i = $count - 1; $i > -1; $i --) {
                $s = substr_replace($s, $replaceWith, (int) $matches[$i][0][1], strlen($matches[$i][0][0]));
            }
        }

        return $s;
	}

    private function parseNode(string $s): array
    {
        $nodes = [];
        $currentPos = 0;

        while (true) {
            $openingTagPos = strpos($s, '<', $currentPos);
            if ($openingTagPos === false) break;

            $closingTagPos = strpos($s, '>', $currentPos);
            $currentPos = $openingTagPos + 1;

            $tagContents = substr($s, $currentPos - 1, $closingTagPos - $openingTagPos + 1);
            $tagContents = preg_replace('!\s+!', ' ', $tagContents);
            $tagContents = str_replace(['<', '>'], '', $tagContents);
            $attributeParser = new AttributeParser(tag: $tagContents);
            $attributes = $attributeParser->parse();

            $tagContainsSpaceSymbol = strpos($tagContents, ' ') !== false;
            $tag = $tagContainsSpaceSymbol ? explode(' ', $tagContents)[0] : $tagContents;
            $tag = mb_strtolower($tag);

            $selfClosingTag = in_array($tag, self::SELF_CLOSING_TAGS);
            if ($selfClosingTag) {
                $nodes[] = new Node(
                    raw: substr($s, $openingTagPos, $closingTagPos - $openingTagPos + 1), 
                    children: [],
                    attributes: $attributes,
                    tag: $tag
                );
                $currentPos = $closingTagPos + 1;
            } else {
                $tagEndPosition = $this->findTagEndPosition(s: $s, pos: $currentPos);

                $tagContents = substr($s, $currentPos - 1, $tagEndPosition - $currentPos + 2);
                $innerContentStart = strpos($tagContents, '>') + $currentPos;
                $innerContentEnd = strrpos($tagContents, '<') + $currentPos - 1;

                $nodes[] = new Node(
                    raw: $tagContents, 
                    children: $this->parseNode(s: substr($s, $innerContentStart, $innerContentEnd - $innerContentStart)),
                    attributes: $attributes,
                    tag: $tag
                );

                $currentPos = $tagEndPosition + 1;
            }
        }

        return $nodes;
    }

    private function findTagEndPosition(string $s, int $pos): int
    {
        $openedTagCount = 1;
        $closedTagCount = 0;
        $resultPos = strlen($s);

        $openedTagClosePosition = strpos($s, '>', $pos);
        if ($openedTagClosePosition === false) return $resultPos;

        $currentPos = $openedTagClosePosition + 1;

        while (true) {
            $nextOpeningTagStart = strpos($s, '<', $currentPos);
            if ($nextOpeningTagStart === false) return $resultPos;

            $nextOpeningTagEnd = strpos($s, '>', $nextOpeningTagStart);
            
            $tagContents = substr($s, $nextOpeningTagStart, $nextOpeningTagEnd - $nextOpeningTagStart + 1);
            $tagContents = str_replace(['<', '>'], '', $tagContents);
            $tagContents = preg_replace('!\s+!', ' ', $tagContents);
            $tagContents = trim($tagContents);

            $tag = strpos($tagContents, ' ') === false ? $tagContents : explode(' ', $tagContents)[0];
            $tag = mb_strtolower($tag);

            $selfClosingTag = in_array($tag, self::SELF_CLOSING_TAGS);
            if ($selfClosingTag) {
                $openedTagCount ++;
                $closedTagCount ++;
            } else {
                if (str_starts_with($tag, '/')) {
                    $closedTagCount ++;
                } else {
                    $openedTagCount ++;
                }

                if ($openedTagCount === $closedTagCount) {
                    $resultPos = $nextOpeningTagEnd;
                    break;
                }
            }

            $currentPos = $nextOpeningTagEnd;
        }

        return $resultPos;
    }
}
<?php

namespace Siarko\BlockLayout\Definitions;

class TagParserManager
{

    /**
     * @param TagParserInterface[] $parsers
     */
    public function __construct(
        protected readonly array $parsers = []
    )
    {
    }

    /**
     * @param string $tagName
     * @return TagParserInterface|null
     */
    public function getParser(string $tagName): ?TagParserInterface
    {
        if(array_key_exists($tagName, $this->parsers)){
            return $this->parsers[$tagName];
        }
        return null;
    }

}
<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\AbstractTagParser;
use Siarko\BlockLayout\Definitions\TagData;

class Title extends AbstractTagParser
{
    public const BLOCK_ID = 'title';
    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        $id = ($element->attributes()->id) ?? static::BLOCK_ID;
        return $this->tagDataFactory->create([
            'id' => (string)$id, //casting is for text node
            'type' => 'title',
            'extraData' => [
                'text' => trim((string)$element)
            ]
        ]);
    }

    public function shouldParseChildren(): bool
    {
        return false;
    }
}
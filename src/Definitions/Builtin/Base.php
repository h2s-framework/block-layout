<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\AbstractTagParser;
use Siarko\BlockLayout\Definitions\TagData;

class Base extends AbstractTagParser
{

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        return $this->tagDataFactory->create([
            'id' => 'base',
            'type' => 'base',
            'extraData' => [
                'href' => $element->attributes()->href->__toString()
            ]
        ]);
    }

    public function shouldParseChildren(): bool
    {
        return false;
    }
}
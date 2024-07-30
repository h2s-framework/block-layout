<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\AbstractTagParser;
use Siarko\BlockLayout\Definitions\TagData;

class Delete extends AbstractTagParser
{

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        return $this->tagDataFactory->create([
            'id' => $this->getElementId($element),
            'type' => $element->getName()
        ]);
    }

    public function shouldParseChildren(): bool
    {
        return false;
    }
}
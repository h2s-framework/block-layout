<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\TagData;

abstract class AbstractLinkedTag extends \Siarko\BlockLayout\Definitions\AbstractTagParser
{
    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        return $this->tagDataFactory->create([
            'id' => $this->getElementId($element),
            'type' => $element->getName(),
            'extraData' => [
                'href' => $element->attributes()->href?->__toString()
            ]
        ]);
    }

    public function shouldParseChildren(): bool
    {
        return false;
    }
}
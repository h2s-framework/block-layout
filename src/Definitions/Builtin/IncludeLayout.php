<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\AbstractTagParser;
use Siarko\BlockLayout\Definitions\TagData;

class IncludeLayout extends AbstractTagParser
{

    public const TYPE = 'includeLayout';

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        return $this->tagDataFactory->create([
            'id' => $this->getElementId($element),
            'type' => self::TYPE
        ]);
    }

    public function shouldParseChildren(): bool
    {
        return false;
    }
}
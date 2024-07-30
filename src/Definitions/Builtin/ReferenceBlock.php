<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\TagData;

class ReferenceBlock extends Block
{

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        $result = parent::parse($element, $childData);
        $result->setType('referenceBlock');
        return $result;
    }
}
<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\AbstractTagParser;
use Siarko\BlockLayout\Definitions\TagData;

class Block extends AbstractTagParser
{

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        $result = $this->tagDataFactory->create([
            'id' => $this->getElementId($element),
            'type' => 'block'
        ]);
        if($element->attributes()->template !== null){
            $result->addExtraData('template', (string)$element->attributes()->template);
        }
        foreach ($element->children() as $child) {
            if($child->getName() === 'data'){
                $result->addExtraData('data', $this->readDataNodes($child));
            }
        }
        foreach ($childData as $childType => $typeData) {
            $result->addChildren($childType, array_keys($typeData));
        }
        return $result;
    }

    public function shouldParseChildren(): bool
    {
        return true;
    }
}
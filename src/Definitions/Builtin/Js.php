<?php

namespace Siarko\BlockLayout\Definitions\Builtin;

use Siarko\BlockLayout\Definitions\TagData;

class Js extends AbstractLinkedTag
{

    public const SCRIPT_TYPE_ARGUMENT = 'type';

    /**
     * @param \SimpleXMLElement $element
     * @param array $childData
     * @return TagData
     */
    public function parse(\SimpleXMLElement $element, array $childData = []): TagData
    {
        $linkedTag = parent::parse($element, $childData);
        if($scriptType = $element->attributes()->type){
            $linkedTag->addExtraData(self::SCRIPT_TYPE_ARGUMENT, $scriptType->__toString());
        }
        return $linkedTag;
    }

}
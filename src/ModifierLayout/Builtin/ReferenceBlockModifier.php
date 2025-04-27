<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\BlockInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\BlockLayout\Exception\ReferencedBlockNotFound;

class ReferenceBlockModifier implements ModifierInterface
{

    public function getPriority(): int
    {
        return 20;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        if(!array_key_exists($modifier[TagData::ID], $mainLayout[BlockInterface::TYPE])){
            throw new ReferencedBlockNotFound($modifier[TagData::ID]);
        }
        $block = &$mainLayout[BlockInterface::TYPE][$modifier[TagData::ID]];
        if(array_key_exists(BlockInterface::ATTRIBUTE_TEMPLATE, $modifier)){ //overwrite template ID if exists in modifier
            $block[BlockInterface::ATTRIBUTE_TEMPLATE] = $modifier[BlockInterface::ATTRIBUTE_TEMPLATE];
        }
        $block[TagData::CHILDREN] = array_merge_recursive($block[TagData::CHILDREN], $modifier[TagData::CHILDREN]); //merge children

        if(array_key_exists('data', $modifier)){//merge template data //TODO fix probably
            if(!array_key_exists('data', $block)){
                $block['data'] = [];
            }
            $block['data'] = array_merge_recursive($block['data'], $modifier['data']);
        }
        return $mainLayout;
    }
}
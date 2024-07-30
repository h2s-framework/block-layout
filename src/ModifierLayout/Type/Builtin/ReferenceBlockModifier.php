<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

use Siarko\BlockLayout\Exception\ReferencedBlockNotFound;
use Siarko\BlockLayout\ModifierLayout\Type\IModifierType;

class ReferenceBlockModifier implements IModifierType
{

    public function getPriority(): int
    {
        return 20;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        if(!array_key_exists($modifier['id'], $mainLayout['block'])){
            throw new ReferencedBlockNotFound($modifier['id']);
        }
        $block = &$mainLayout['block'][$modifier['id']];
        if(array_key_exists('template', $modifier)){ //overwrite template ID if exists in modifier
            $block['template'] = $modifier['template'];
        }
        $block['children'] = array_merge_recursive($block['children'], $modifier['children']); //merge children

        if(array_key_exists('data', $modifier)){//merge template data
            if(!array_key_exists('data', $block)){
                $block['data'] = [];
            }
            $block['data'] = array_merge_recursive($block['data'], $modifier['data']);
        }
        return $mainLayout;
    }
}
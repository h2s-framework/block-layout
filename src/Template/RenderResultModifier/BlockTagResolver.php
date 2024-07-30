<?php

namespace Siarko\BlockLayout\Template\RenderResultModifier;

use Siarko\BlockLayout\Block;

class BlockTagResolver implements RenderResultModifierInterface
{

    public const REGEX_SINGLE_BLOCK = "#<block\s+id=\"(?<blockId>[a-zA-Z0-9-_\.]+)\"\s*/>#i";

    public const REGEX_MULTIPLE_BLOCKS = "#<blocks\s*(all)?/>#i";

    /**
     * @param string $result
     * @param Block $block
     * @return string
     * @throws \Siarko\BlockLayout\Exception\TemplateFileNotFound
     */
    public function apply(string $result, Block $block): string
    {
        $usedIds = [];
        $result = preg_replace_callback(self::REGEX_SINGLE_BLOCK,
            function($match) use ($block, &$usedIds){
                $id = $match['blockId'];
                $usedIds[] = $id;
                $block = $block->getChild($id);
                if($block instanceof Block){
                    return $block->render();
                }
                return '';
        }, $result);
        $freeIds = array_diff($block->getChildBlockIds(), $usedIds);
        return preg_replace_callback(self::REGEX_MULTIPLE_BLOCKS,
            function($match) use ($block, $freeIds){
                if(count($match) == 2){ //all blocks
                    $blocks = $block->getChildren();
                }else{
                    $blocks = $block->getChildren($freeIds);
                }
                return implode('', array_map(
                    function($block){
                        return $block->render();
                    }, $blocks)
                );
            }
        , $result);
    }
}
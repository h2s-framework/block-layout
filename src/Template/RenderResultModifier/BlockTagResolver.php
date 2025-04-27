<?php

namespace Siarko\BlockLayout\Template\RenderResultModifier;

use Siarko\BlockLayout\Api\Template\RenderResultModifierInterface;
use Siarko\BlockLayout\Blocks\Block;
use Siarko\BlockLayout\Exception\TemplateFileNotFound;
use Siarko\BlockLayout\Layout;

class BlockTagResolver implements RenderResultModifierInterface
{

    public const REGEX_SINGLE_CHILD = "#<child\s+id=\"(?<blockId>[a-zA-Z0-9-_\.]+)\"\s*/>#i";
    public const REGEX_SINGLE_BLOCK = "#<block\s+id=\"(?<blockId>[a-zA-Z0-9-_\.]+)\"\s*/>#i";

    public const REGEX_MULTIPLE_CHILDREN = "#<children\s*(all)?/>#i";

    /**
     * @param string $result
     * @param Block $block
     * @param Layout $layout
     * @return string
     * @throws TemplateFileNotFound
     */
    public function apply(string $result, Block $block, Layout $layout): string
    {
        $result = preg_replace_callback(self::REGEX_SINGLE_BLOCK,
            function($match) use ($block, $layout){
                return (($block = $layout->getBlock($match['blockId'])) instanceof Block) ? $block->render() : '';
            }, $result);
        $usedIds = [];
        $result = preg_replace_callback(self::REGEX_SINGLE_CHILD,
            function($match) use ($block, &$usedIds){
                $usedIds[] = $id = $match['blockId'];
                return (($block = $block->getChild($id)) instanceof Block) ? $block->render() : '';
        }, $result);
        $freeIds = array_diff($block->getChildrenIds(), $usedIds);
        return preg_replace_callback(self::REGEX_MULTIPLE_CHILDREN,
            function($match) use ($block, $freeIds){
                $blocks = (count($match) == 2) ? $block->getChildren() : $block->getChildren($freeIds);
                return implode('', array_map( fn($block) => $block->render(), $blocks));
            }
        , $result);
    }
}
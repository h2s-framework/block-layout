<?php

namespace Siarko\BlockLayout\Template\RenderResultModifier;

use Siarko\BlockLayout\Block;

interface RenderResultModifierInterface
{
    public function apply(string $result, Block $block): string;

}
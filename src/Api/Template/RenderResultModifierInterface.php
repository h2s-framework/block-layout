<?php

namespace Siarko\BlockLayout\Api\Template;

use Siarko\BlockLayout\Blocks\Block;
use Siarko\BlockLayout\Layout;

interface RenderResultModifierInterface
{
    public function apply(string $result, Block $block, Layout $layout): string;

}
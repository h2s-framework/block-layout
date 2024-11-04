<?php

namespace Siarko\BlockLayout\Argument;

use Siarko\BlockLayout\Block;

interface BlockAwareArgumentInterface extends BlockArgumentInterface
{
    /**
     * @param Block $block
     * @return mixed
     */
    public function setBlock(Block $block);

}
<?php

namespace Siarko\BlockLayout\Api\Layout;

use Siarko\BlockLayout\Exception\UnknownBlockType;

interface BlockTypeProviderInterface
{

    public const BLOCK_TYPE = 'block';

    public function typeExists(string $blockType): bool;

    /**
     * @throws UnknownBlockType
     */
    public function getClassType(string $blockType): string;
}
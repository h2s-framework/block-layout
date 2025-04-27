<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\BlockLayout\Api\Layout\BlockTypeProviderInterface;
use Siarko\BlockLayout\Exception\UnknownBlockType;

class BlockTypeProvider implements BlockTypeProviderInterface
{

    public function __construct(
        private readonly array $types = []
    )
    {
    }

    public function typeExists(string $blockType): bool
    {
        return array_key_exists($blockType, $this->types);
    }

    /**
     * @throws UnknownBlockType
     */
    public function getClassType(string $blockType): string
    {
        return $this->types[$blockType] ?? throw new UnknownBlockType($blockType);
    }
}
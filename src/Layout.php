<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Api\Layout\BlockCollectionBuilderInterface;
use Siarko\BlockLayout\Blocks\Block;
use Siarko\BlockLayout\Exception\TemplateFileNotFound;

class Layout
{


    /**
     * @param array<callable|Block> $blockList
     */
    public function __construct(
        private readonly array $blockList = []
    )
    {
    }

    /**
     * @param string $id
     * @return Block|null
     */
    public function getBlock(string $id):?Block{
        if(array_key_exists($id, $this->blockList)){
            $entry = $this->blockList[$id];
            return ($entry instanceof Block) ? $entry : $entry();
        }
        return null;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getBlocks(array $ids): array
    {
        return array_filter(array_map(fn($id) => $this->getBlock($id), $ids), fn($block) => $block !== null);
    }

    /**
     * @return string
     * @throws TemplateFileNotFound
     */
    public function render(): string
    {
        $rootBlock = $this->getBlock(BlockCollectionBuilderInterface::ROOT_BLOCK_ID);
        return $rootBlock->render();
    }


}
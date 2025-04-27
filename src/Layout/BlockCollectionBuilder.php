<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\Api\Factory\FactoryProviderInterface;
use Siarko\BlockLayout\Api\Layout\BlockCollectionBuilderInterface;
use Siarko\BlockLayout\Api\Layout\BlockTypeProviderInterface;
use Siarko\BlockLayout\Blocks\Block;
use Siarko\BlockLayout\Exception\RootBlockNotFound;
use Siarko\BlockLayout\Exception\UnknownBlockType;

class BlockCollectionBuilder implements BlockCollectionBuilderInterface
{
    public function __construct(
        private readonly BlockTypeProviderInterface $blockTypeProvider,
        private readonly FactoryProviderInterface $factoryProvider,
        private array $layout = []
    ) {
    }

    public function setLayout(array $layout): static
    {
        $this->layout = $layout;
        return $this;
    }

    public function build(): array
    {
        if(!array_key_exists(self::ROOT_BLOCK_ID, $this->getBlocksByType())){
            throw new RootBlockNotFound();
        }
        $result = [];
        foreach ($this->layout as $type => $item) {
            foreach ($item as $blockId => $blockData) {
                $result[$blockId] = function() use ($blockId, $type){
                    return $this->createBlock($blockId, $type);
                };
            }
        }
        return $result;
    }

    private function getBlocksByType(string $type = BlockTypeProviderInterface::BLOCK_TYPE): array
    {
        return $this->layout[$type] ?? [];
    }

    /**
     * @throws UnknownBlockType
     */
    private function createBlock(string $blockId, string $type = BlockTypeProviderInterface::BLOCK_TYPE): ?Block{
        $blockClassType = $this->blockTypeProvider->getClassType($type);
        $blockData = $this->getBlocksByType($type)[$blockId];
        if(!array_key_exists('data', $blockData)){$blockData['data'] = [];}
        /** @var Block $instance */
        $instance = $this->factoryProvider->getFactory($blockClassType)->create($blockData);
        $instance->processAdditionalData($blockData);
        return $instance;
    }

}
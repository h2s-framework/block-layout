<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Exception\UnknownBlockType;
use Siarko\Api\Factory\FactoryProviderInterface;

class Layout
{

    /**
     * Set of assoc array data describing blocks
     * @var array
     */
    private array $layoutStructure = [];

    /**
     * Constructor functions constructing and returning blocks
     * @var callable[]|Block[]
     */
    private array $blockList = [];

    /**
     * @param FactoryProviderInterface $factoryProvider
     * @param array $extraBlockTypeMap
     */
    public function __construct(
        protected readonly FactoryProviderInterface $factoryProvider,
        protected readonly array $extraBlockTypeMap = []
    )
    {
    }

    /**
     * @param array $blockStructure
     * @return void
     */
    public function setLayoutStructure(array $blockStructure){
        $this->layoutStructure = $blockStructure;
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
     * @return string
     * @throws Exception\RootBlockNotFound
     */
    public function render(): string
    {
        $this->createBlocks();
        $rootBlock = $this->getBlock('root');
        return $rootBlock->render();
    }

    protected function getBlocks(string $type = 'block'): array
    {
        return $this->layoutStructure[$type];
    }

    /**
     * @return void
     * @throws \Siarko\BlockLayout\Exception\RootBlockNotFound
     */
    private function createBlocks()
    {
        if(!array_key_exists('root', $this->getBlocks())){
            throw new \Siarko\BlockLayout\Exception\RootBlockNotFound();
        }
        $this->blockList = [];
        foreach ($this->getBlocks() as $blockId => $blockData) {
            if(array_key_exists($blockId, $this->getBlocks())){
                $this->blockList[$blockId] = function() use ($blockId){
                    return $this->createBlock($blockId);
                };
            }
        }
    }

    private function createBlock(string $blockId, string $type = 'block'): ?Block{
        if(!array_key_exists($type, $this->extraBlockTypeMap)){
            throw new UnknownBlockType($type);
        }
        $blockData = $this->getBlocks($type)[$blockId];
        if(!array_key_exists('data', $blockData)){$blockData['data'] = [];}
        $blockData['layout'] = $this;
        $blockData['childBlockIds'] = $this->constructBlockChildren($blockData['children']);
        /** @var Block $instance */
        $instance = $this->factoryProvider->getFactory(
            $this->extraBlockTypeMap[$type]
        )?->create($blockData);
        $instance->processAdditionalData($blockData);
        return $instance;
    }

    protected function constructBlockChildren(array $blockChildren): array
    {
        $result = [];
        foreach ($blockChildren as $childType => $childSet) {
            foreach ($childSet as $childId) {
                if(!array_key_exists($childId, $this->getBlocks($childType))){continue;}
                $result[] = $childId;
                if($childType == 'block'){ continue; }

                $this->blockList[$childId] = function() use ($childId, $childType){
                    return $this->createBlock($childId, $childType);
                };
            }
        }
        return $result;
    }

}
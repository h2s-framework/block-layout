<?php

namespace Siarko\BlockLayout\Definitions;

use Siarko\BlockLayout\Exception\BlockDataTypeNotSet;
use Siarko\BlockLayout\Template\DataNodeFactory;

abstract class AbstractTagParser implements TagParserInterface
{

    protected static $AUTO_INDICES = [];

    public function __construct(
        protected readonly TagDataFactory $tagDataFactory,
        protected readonly DataNodeFactory $dataNodeFactory
    )
    {
    }

    protected function getElementId(\SimpleXMLElement $element): string
    {
        $attributes = $element->attributes();
        if($attributes->id !== null){
            return $attributes->id->__toString();
        }else{
            if(!array_key_exists($element->getName(), self::$AUTO_INDICES)){
                self::$AUTO_INDICES[$element->getName()] = 0;
            }
            $element->addAttribute('id', $element->getName().'_'.self::$AUTO_INDICES[$element->getName()]++);
            return $element->attributes()->id->__toString();
        }
    }


    /**
     * @param \SimpleXMLElement $element
     * @return array
     * @throws BlockDataTypeNotSet
     */
    protected function readDataNodes(\SimpleXMLElement $element): array
    {
        $result = [];
        foreach ($element->children() as $child) {
            if(!$child->attributes()->type){
                throw new \Siarko\BlockLayout\Exception\BlockDataTypeNotSet($child->getName());
            }
            $result[$child->getName()] = $this->dataNodeFactory->create([
                'name' => $child->getName(),
                'type' => $child->attributes()->type->__toString(),
                'value' => $child->__toString()
            ]);
        }
        return $result;
    }

}
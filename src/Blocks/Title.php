<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\BlockLayout\Api\Layout\Definitions\TitleInterface;
use Siarko\Utils\Exceptions\TypeCastException;

class Title extends Block
{
    public function getTemplateId(): ?string
    {
        if($this->id === TitleInterface::BLOCK_ID){
            return parent::getTemplateId() ?? TitleInterface::BLOCK_ID;
        }
        return null;
    }


    /**
     * @param array $describerData
     * @return void
     * @throws TypeCastException
     */
    public function processAdditionalData(array $describerData): void
    {
        $this->updateTemplateData([$this->dataNodeFactory->createNamed(
            name: TitleInterface::ATTRIBUTE_TEXT,
            type: 'string',
            value: $describerData[TitleInterface::ATTRIBUTE_TEXT]
        )]);
    }
}
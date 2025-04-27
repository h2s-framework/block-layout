<?php

namespace Siarko\BlockLayout\Blocks;


use Siarko\BlockLayout\Api\Layout\Definitions\BaseInterface;

class BaseUrl extends Block
{

    public function getTemplateId(): ?string
    {
        return parent::getTemplateId() ?? 'base_url';
    }
    public function processAdditionalData(array $describerData)
    {
        $this->updateTemplateData([
            $this->dataNodeFactory->createNamed(
                name: BaseInterface::ATTRIBUTE_HREF,
                type: 'string',
                value: $describerData[BaseInterface::ATTRIBUTE_HREF],
            ),
            $this->dataNodeFactory->createNamed(
                name: BaseInterface::ATTRIBUTE_TARGET,
                type: 'string',
                value:  $describerData[BaseInterface::ATTRIBUTE_TARGET] ?? '_self',
            ),
        ]);
    }
}
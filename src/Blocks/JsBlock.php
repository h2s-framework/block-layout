<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\Api\State\AppState;
use Siarko\BlockLayout\Api\Layout\Definitions\JsInterface;
use Siarko\DependencyManager\Attributes\InjectField;

class JsBlock extends Block
{


    #[InjectField]
    protected AppState $appState;

    /**
     * @return string|null
     */
    public function getTemplateId(): ?string
    {
        return parent::getTemplateId() ?? 'js_include';
    }

    /**
     * @param array $describerData
     * @return void
     */
    public function processAdditionalData(array $describerData)
    {
        $this->updateTemplateData([
            $this->dataNodeFactory->createNamed(
                name: JsInterface::ATTRIBUTE_HREF,
                type: 'string',
                value: $describerData[JsInterface::ATTRIBUTE_HREF]
            ),
            $this->dataNodeFactory->createNamed(
                name: JsInterface::ATTRIBUTE_TYPE,
                type: 'string',
                value: $describerData[JsInterface::ATTRIBUTE_TYPE] ?? 'text/javascript'
            )
        ]);
    }

}
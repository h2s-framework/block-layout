<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\Api\State\AppState;
use Siarko\BlockLayout\Api\Layout\Definitions\LinkedBlockInterface;
use Siarko\DependencyManager\Attributes\InjectField;
use Siarko\Utils\Exceptions\TypeCastException;

class CssLink extends Block
{

    /**
     * @var AppState $appState
     */
    #[InjectField]
    protected AppState $appState;
    public function getTemplateId(): ?string
    {
        return parent::getTemplateId() ?? 'css_include';
    }

    /**
     * @param array $describerData
     * @return void
     * @throws TypeCastException
     */
    public function processAdditionalData(array $describerData)
    {
        $this->updateTemplateData([$this->dataNodeFactory->create([
            'name' => LinkedBlockInterface::ATTRIBUTE_HREF,
            'type' => 'string',
            'value' => $describerData[LinkedBlockInterface::ATTRIBUTE_HREF]
        ])]);
    }

}
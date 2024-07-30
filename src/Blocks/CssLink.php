<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\Api\State\AppMode;
use Siarko\Api\State\AppState;
use Siarko\DependencyManager\Attributes\InjectField;
use Siarko\Utils\Strings;

class CssLink extends BuiltinBlock
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
     */
    public function processAdditionalData(array $describerData)
    {
        if($this->appState->isAppMode(AppMode::DEV)){
            $describerData['href'] .= '?__='.Strings::generateRandomString();
        }
        $this->updateTemplateData($this->dataNodeFactory->create([
            'name' => 'href',
            'type' => 'string',
            'value' => $describerData['href']
        ]));
    }

}
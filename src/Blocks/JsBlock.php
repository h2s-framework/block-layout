<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\Api\State\AppMode;
use Siarko\Api\State\AppState;
use Siarko\BlockLayout\Definitions\Builtin\Js;
use Siarko\DependencyManager\Attributes\InjectField;
use Siarko\Utils\Strings;

class JsBlock extends BuiltinBlock
{

    public const SCRIPT_TYPE_ARGUMENT = 'type';

    public const SCRIPT_HREF_ARGUMENT = 'href';

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
        if($this->appState->isAppMode(AppMode::DEV)){
            $describerData[self::SCRIPT_HREF_ARGUMENT] .= '?__='.Strings::generateRandomString();
        }
        $templateData = [$this->dataNodeFactory->create([
            'name' => self::SCRIPT_HREF_ARGUMENT,
            'type' => 'string',
            'value' => $describerData[self::SCRIPT_HREF_ARGUMENT]
        ])];
        $scriptType = $describerData[Js::SCRIPT_TYPE_ARGUMENT] ?? 'application/javascript';
        $templateData[] = $this->dataNodeFactory->create([
            'name' => self::SCRIPT_TYPE_ARGUMENT,
            'type' => 'string',
            'value' => $scriptType
        ]);
        $this->updateTemplateData($templateData);
    }

}
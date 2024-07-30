<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\Paths\Provider\AbstractPathProvider;

class Title extends BuiltinBlock
{
    public function getTemplateId(): ?string
    {
        if($this->id === \Siarko\BlockLayout\Definitions\Builtin\Title::BLOCK_ID){
            return parent::getTemplateId() ?? 'title';
        }
        return null;
    }


    public function processAdditionalData(array $describerData)
    {
        $this->updateTemplateData($this->dataNodeFactory->create([
            'name' => 'text',
            'type' => 'string',
            'value' => $describerData['text']
        ]));
    }
}
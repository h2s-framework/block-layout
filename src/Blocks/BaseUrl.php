<?php

namespace Siarko\BlockLayout\Blocks;


class BaseUrl extends BuiltinBlock
{

    public function getTemplateId(): ?string
    {
        return parent::getTemplateId() ?? 'base_url';
    }
    public function processAdditionalData(array $describerData)
    {
        $this->updateTemplateData([
            $this->dataNodeFactory->create([
                'name' => 'href',
                'type' => 'string',
                'value' => $describerData['href']
            ]),
            $this->dataNodeFactory->create([
                'name' => 'target',
                'type' => 'string',
                'value' =>  $describerData['target'] ?? '_self'
            ])
        ]);
    }
}
<?php

namespace Siarko\BlockLayout\Config\Provider;

use Siarko\ConfigFiles\Api\Provider\ConfigFileNameProviderInterface;

class LayoutFileNameProvider implements ConfigFileNameProviderInterface
{

    public function getFileName(string $type): string
    {
        $fileName = str_replace('/', DIRECTORY_SEPARATOR, $type);
        return str_replace('\\', '\\\\', $fileName);
    }
}
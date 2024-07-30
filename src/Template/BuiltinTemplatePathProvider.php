<?php

namespace Siarko\BlockLayout\Template;

use Siarko\Paths\Provider\AbstractPathProvider;

class BuiltinTemplatePathProvider extends AbstractPathProvider
{
    protected function getPaths($id = null)
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Builtin'.($id != null ? DIRECTORY_SEPARATOR.$id : '');
    }
}
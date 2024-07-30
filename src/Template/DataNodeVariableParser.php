<?php

namespace Siarko\BlockLayout\Template;

use Siarko\BlockLayout\Template\Variable\DataNodeVariableValueProvider;

class DataNodeVariableParser
{
    /**
     * @param DataNodeVariableValueProvider[] $valueProviders
     */
    public function __construct(
        protected array $valueProviders = []
    )
    {
    }

    public function parse(string $name, string $value): string
    {
        foreach ($this->valueProviders as $valueProvider) {
            $value = str_replace($valueProvider->getName(), $valueProvider->getValue(), $value);
        }
        return $value;
    }

}
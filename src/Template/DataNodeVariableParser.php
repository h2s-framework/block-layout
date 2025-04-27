<?php

namespace Siarko\BlockLayout\Template;

use Siarko\BlockLayout\Api\Template\DataNodeVariableValueProvider;

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

    /**
     * @param string $name
     * @param string $value
     * @return string
     */
    public function parse(string $name, string $value): string
    {
        foreach ($this->valueProviders as $variableName => $valueProvider) {
            $value = str_replace('$'.$variableName, $valueProvider->getValue(), $value);
        }
        return $value;
    }

}
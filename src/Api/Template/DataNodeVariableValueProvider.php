<?php

namespace Siarko\BlockLayout\Api\Template;

interface DataNodeVariableValueProvider
{

    /**
     * @return string
     */
    public function getValue(): string;

}
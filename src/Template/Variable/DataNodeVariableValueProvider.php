<?php

namespace Siarko\BlockLayout\Template\Variable;

interface DataNodeVariableValueProvider
{

    public function getValue(): string;

    public function getName(): string;
}
<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

use Siarko\BlockLayout\ModifierLayout\Type\IModifierType;

class IncludeLayoutModifier implements IModifierType
{

    public function getPriority(): int
    {
        return 5;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        return $mainLayout;
    }
}
<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

class BlockModifier implements \Siarko\BlockLayout\ModifierLayout\Type\IModifierType
{

    public function getPriority(): int
    {
        return 10;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        $mainLayout['block'][$modifier['id']] = $modifier;
        return $mainLayout;
    }
}
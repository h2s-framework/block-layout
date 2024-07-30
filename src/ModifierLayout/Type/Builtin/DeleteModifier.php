<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

class DeleteModifier implements \Siarko\BlockLayout\ModifierLayout\Type\IModifierType
{
    public function getPriority(): int
    {
        return 30;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        $type = (array_key_exists('type', $modifier) ? $modifier['type'] : 'block');
        unset($mainLayout[$type][$modifier['id']]);
        return $mainLayout;
    }
}
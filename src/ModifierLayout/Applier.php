<?php

namespace Siarko\BlockLayout\ModifierLayout;

class Applier
{

    public function __construct(
        private readonly Sorter $modifierSorter,
        private readonly \Siarko\BlockLayout\ModifierLayout\Type\ModifierTypeProvider $modifierTypeProvider
    )
    {
    }

    public function apply(array $mainLayout, array $layoutModifierSet): array{
        $layoutModifierSet = $this->modifierSorter->sort($layoutModifierSet);
        foreach ($layoutModifierSet as $modifierType => $modifiers) {
            foreach ($modifiers as $modifier) {
                $type = $this->modifierTypeProvider->getType($modifierType);
                $mainLayout = $type->apply($mainLayout, $modifier);
            }
        }
        return $mainLayout;
    }
}
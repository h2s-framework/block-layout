<?php

namespace Siarko\BlockLayout\ModifierLayout;

class Sorter
{

    /**
     * @param ModifierProvider $modifierTypeProvider
     */
    public function __construct(
        private readonly ModifierProvider $modifierTypeProvider
    )
    {
    }

    public function sort(array $modifier): array
    {
        uksort($modifier, function($a, $b){
            return $this->modifierTypeProvider->getModifier($a)->getPriority() - $this->modifierTypeProvider->getModifier($b)->getPriority();
        });
        return $modifier;
    }
}
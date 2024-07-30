<?php

namespace Siarko\BlockLayout\ModifierLayout;

use Siarko\BlockLayout\ModifierLayout\Type\IModifierType;

class Sorter
{

    /**
     * @param Type\ModifierTypeProvider $modifierTypeProvider
     */
    public function __construct(
        private readonly \Siarko\BlockLayout\ModifierLayout\Type\ModifierTypeProvider $modifierTypeProvider
    )
    {
    }

    public function sort(array $modifier): array
    {
        uksort($modifier, function($a, $b){
            return $this->modifierTypeProvider->getType($a)->getPriority() - $this->modifierTypeProvider->getType($b)->getPriority();
        });
        return $modifier;
    }
}
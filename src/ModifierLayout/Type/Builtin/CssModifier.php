<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

use Siarko\Utils\ArrayManager;

class CssModifier implements \Siarko\BlockLayout\ModifierLayout\Type\IModifierType
{

    public function __construct(
        protected readonly ArrayManager $arrayManager
    )
    {
    }

    public function getPriority(): int
    {
        return 22;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        return $this->arrayManager->set('css/'.$modifier['id'], $mainLayout, $modifier);
    }
}
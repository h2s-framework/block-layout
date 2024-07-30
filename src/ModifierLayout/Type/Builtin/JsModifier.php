<?php

namespace Siarko\BlockLayout\ModifierLayout\Type\Builtin;

use Siarko\Utils\ArrayManager;

class JsModifier implements \Siarko\BlockLayout\ModifierLayout\Type\IModifierType
{

    public function __construct(
        protected readonly ArrayManager $arrayManager
    )
    {
    }

    public function getPriority(): int
    {
        return 24;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        return $this->arrayManager->set('js/'.$modifier['id'], $mainLayout, $modifier);
    }
}
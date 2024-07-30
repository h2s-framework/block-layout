<?php

namespace Siarko\BlockLayout\ModifierLayout\Type;

interface IModifierType
{

    public function getPriority(): int;

    public function apply(array $mainLayout, array $modifier): array;

}
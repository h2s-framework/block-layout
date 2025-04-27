<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;

class IncludeLayoutModifier implements ModifierInterface
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
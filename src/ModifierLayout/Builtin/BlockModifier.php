<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\BlockInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;

class BlockModifier implements ModifierInterface
{

    public function getPriority(): int
    {
        return 10;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        $mainLayout[BlockInterface::TYPE][$modifier[TagData::ID]] = $modifier;
        return $mainLayout;
    }
}
<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\CssInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\Utils\ArrayManager;

class CssModifier implements ModifierInterface
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
        return $this->arrayManager->set(CssInterface::TYPE.'/'.$modifier[TagData::ID], $mainLayout, $modifier);
    }
}
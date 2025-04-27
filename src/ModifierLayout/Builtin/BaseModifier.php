<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\BaseInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\Utils\ArrayManager;

class BaseModifier implements ModifierInterface
{

    public function __construct(
        protected readonly ArrayManager $arrayManager
    )
    {
    }

    public function getPriority(): int
    {
        return 23;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        return $this->arrayManager->set(BaseInterface::TYPE.'/'.$modifier[TagData::ID], $mainLayout, $modifier);
    }
}
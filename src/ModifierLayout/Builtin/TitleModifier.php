<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\TitleInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\Utils\ArrayManager;

class TitleModifier implements ModifierInterface
{

    public function __construct(
        protected readonly ArrayManager $arrayManager
    )
    {
    }

    public function getPriority(): int
    {
        return 21;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        return $this->arrayManager->set(TitleInterface::TYPE.'/'.$modifier[TagData::ID], $mainLayout, $modifier);
    }
}
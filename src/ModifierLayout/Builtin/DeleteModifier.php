<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\DeleteInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;

class DeleteModifier implements ModifierInterface
{
    public function getPriority(): int
    {
        return 30;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        $type = (
            array_key_exists(DeleteInterface::ATTRIBUTE_TYPE, $modifier) ?
            $modifier[DeleteInterface::ATTRIBUTE_TYPE] :
            DeleteInterface::ATTRIBUTE_TYPE_DEFAULT
        );
        unset($mainLayout[$type][$modifier[TagData::ID]]);
        return $mainLayout;
    }
}
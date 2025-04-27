<?php

namespace Siarko\BlockLayout\ModifierLayout\Builtin;

use Siarko\BlockLayout\Api\Layout\Definitions\DataInterface;
use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\Utils\ArrayManager;

class DataModifier implements ModifierInterface
{

    public function __construct(
        protected readonly ArrayManager $arrayManager
    )
    {
    }

    public function getPriority(): int
    {
        return 15;
    }

    public function apply(array $mainLayout, array $modifier): array
    {
        $id = $modifier[TagData::ID];
        if(!($targetNodeType = $modifier[DataInterface::TARGET_NODE_TYPE] ?? false)){
            return $mainLayout;
        }
        $targetNodeId = substr($id, strpos($id, '@') + 1);
        $children = $this->arrayManager->get("{$targetNodeType}/{$targetNodeId}/" . TagData::CHILDREN, $mainLayout, []);
        if(!is_int($key = array_search($id, $children))){
            return $mainLayout;
        }
        $mainLayout = $this->arrayManager->remove("{$targetNodeType}/{$targetNodeId}/".TagData::CHILDREN."/{$key}", $mainLayout);

        return $this->arrayManager->set("{$targetNodeType}/{$targetNodeId}/data", $mainLayout, $modifier['value']);

    }
}
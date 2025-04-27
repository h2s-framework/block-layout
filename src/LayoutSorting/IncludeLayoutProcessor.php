<?php

namespace Siarko\BlockLayout\LayoutSorting;

use Siarko\BlockLayout\Api\Layout\Definitions\IncludeLayoutInterface;
use Siarko\BlockLayout\Api\LayoutSorting\ProcessorInterface;
use Siarko\BlockLayout\Definitions\TagData;
use Siarko\ConfigFiles\Api\Provider\ConfigProviderInterface;

class IncludeLayoutProcessor implements ProcessorInterface
{

    public function __construct(
        private readonly ConfigProviderInterface $configProvider
    )
    {
    }


    /**
     * @param array $layouts
     * @param string $layoutId
     * @param array $layoutData
     * @return array
     */
    public function process(array $layouts, string $layoutId, array $layoutData): array
    {
        if(!array_key_exists(IncludeLayoutInterface::TYPE, $layoutData)){
            return $layouts;
        }

        $includes = $this->processIncludes($layoutData[IncludeLayoutInterface::TYPE]);
        unset($layouts[$layoutId]);
        return [
            ...$layouts,
            ...$includes[0],
            ...[$layoutId => $layoutData],
            ...$includes[1]
        ];
    }

    /**
     * @param array $includes
     * @return array|array[]
     */
    private function processIncludes(array $includes): array
    {
        $result = [[],[]];
        foreach ($includes as $include) {
            $id = $include[TagData::ID];
            $layoutConfig = $this->configProvider->fetch($id);
            $result[$include[IncludeLayoutInterface::ORDER_AFTER] ? 1 : 0][$id] = $layoutConfig;
        }
        return $result;
    }
}
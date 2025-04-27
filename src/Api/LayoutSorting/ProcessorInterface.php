<?php

namespace Siarko\BlockLayout\Api\LayoutSorting;

interface ProcessorInterface
{

    /**
     * @param array $layouts
     * @param string $layoutId
     * @param array $layoutData
     * @return array
     */
    public function process(array $layouts, string $layoutId, array $layoutData): array;
}
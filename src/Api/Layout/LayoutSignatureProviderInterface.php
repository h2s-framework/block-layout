<?php

namespace Siarko\BlockLayout\Api\Layout;

interface LayoutSignatureProviderInterface
{

    /**
     * Returns signature of the merged layout based on activated layouts
     * @param ActiveLayoutListInterface $layoutList
     * @return string
     */
    public function get(ActiveLayoutListInterface $layoutList): string;
}
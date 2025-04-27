<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\Api\State\AppStateInterface;
use Siarko\BlockLayout\Api\Layout\ActiveLayoutListInterface;
use Siarko\BlockLayout\Api\Layout\LayoutSignatureProviderInterface;

class LayoutSignatureProvider implements LayoutSignatureProviderInterface
{

    /**
     * @param AppStateInterface $appState
     */
    public function __construct(
        private readonly AppStateInterface $appState
    )
    {
    }

    /**
     * @param ActiveLayoutListInterface $layoutList
     * @return string
     */
    public function get(ActiveLayoutListInterface $layoutList): string
    {
        $path = $this->appState->getAppScope().'.'. implode('_', $layoutList->getActiveLayouts(true));
        return str_replace('/', '-', $path);
    }
}
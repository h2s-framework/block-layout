<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\BlockLayout\Api\Layout\ActiveLayoutListInterface;
use Siarko\BlockLayout\Api\Layout\LayoutSignatureProviderInterface;
use Siarko\BlockLayout\Api\LayoutProviderInterface;
use Siarko\CacheFiles\Api\CacheSetInterface;

class LayoutProvider implements LayoutProviderInterface
{

    /**
     * @param LayoutLoader $layoutLoader
     * @param CacheSetInterface $configCache
     * @param LayoutSignatureProviderInterface $layoutSignatureProvider
     * @param ActiveLayoutListInterface $activeLayoutList
     */
    public function __construct(
        private readonly LayoutLoader                     $layoutLoader,
        private readonly CacheSetInterface                $configCache,
        private readonly LayoutSignatureProviderInterface $layoutSignatureProvider,
        private readonly ActiveLayoutListInterface        $activeLayoutList
    )
    {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $id = $this->layoutSignatureProvider->get($this->activeLayoutList);
        if($this->configCache->exists($id)){
            return $this->configCache->get($id);
        }
        $layoutData = $this->layoutLoader->loadLayouts();
        $this->configCache->set($id, $layoutData);
        return $layoutData;
    }

}
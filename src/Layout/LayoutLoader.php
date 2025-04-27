<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\BlockLayout\Api\Layout\ActiveLayoutListInterface;
use Siarko\BlockLayout\LayoutSorting\LoadedLayoutSorterFactory;
use Siarko\BlockLayout\ModifierLayout\Applier;
use Siarko\ConfigFiles\Api\Provider\ConfigProviderInterface;

class LayoutLoader
{
    public const PATH_PROVIDER_POOL_TYPE = 'layout';

    public const DEFAULT_FILE_PARSER_TYPE = 'pageLayout';

    /**
     * @param Applier $modifierApplier
     * @param LoadedLayoutSorterFactory $loadedLayoutSorterFactory
     * @param ActiveLayoutListInterface $activeLayoutList
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        private readonly Applier                   $modifierApplier,
        private readonly LoadedLayoutSorterFactory $loadedLayoutSorterFactory,
        private readonly ActiveLayoutListInterface $activeLayoutList,
        private readonly ConfigProviderInterface $configProvider
    )
    {
    }

    /**
     * @return array
     */
    public function loadLayouts(): array
    {
        $layoutSorter = $this->loadedLayoutSorterFactory->create();
        foreach ($this->activeLayoutList->getActiveLayouts(true) as $layout) {
            $layoutConfig = $this->configProvider->fetch($layout);
            $layoutSorter->addLayout($layout, $layoutConfig);
        }
        return $this->applyLayoutUpdates($layoutSorter->getLayouts());
    }

    /**
     * @param array $layouts
     * @return array
     */
    private function applyLayoutUpdates(array $layouts): array
    {
        $mainLayout = [];
        foreach ($layouts as $layout) {
            $mainLayout = $this->modifierApplier->apply($mainLayout, $layout);
        }
        return $mainLayout;
    }

}
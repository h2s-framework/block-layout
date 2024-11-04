<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Api\LayoutProviderInterface;
use Siarko\BlockLayout\Exception\BlockDataTypeNotSet;
use Siarko\BlockLayout\Exception\LayoutNotExists;
use Siarko\BlockLayout\Exception\RootBlockNotFound;
use Siarko\BlockLayout\LayoutFactory as LayoutFactory;
use Siarko\DependencyManager\DependencyManager;

class PageRenderer
{

    public const CURRENT_LAYOUT_TYPE_NAME = '$CURRENT_LAYOUT';

    /**
     * @param LayoutFactory $layoutFactory
     * @param DependencyManager $dependencyManager
     * @param LayoutProviderInterface $layoutProvider
     */
    public function __construct(
        private readonly LayoutFactory $layoutFactory,
        private readonly DependencyManager $dependencyManager,
        private readonly LayoutProviderInterface $layoutProvider
    )
    {
    }

    /**
     * @return LayoutProviderInterface
     */
    public function getLayoutProvider(): LayoutProviderInterface
    {
        return $this->layoutProvider;
    }

    /**
     * @return void
     * @throws BlockDataTypeNotSet
     * @throws LayoutNotExists
     * @throws RootBlockNotFound
     */
    public function render(): void
    {
        $layout = $this->layoutFactory->create();
        $this->dependencyManager->bindObject(self::CURRENT_LAYOUT_TYPE_NAME, $layout);
        $layoutData = $this->layoutProvider->getData();
        $layout->setLayoutStructure($layoutData);
        echo $layout->render();
    }
}
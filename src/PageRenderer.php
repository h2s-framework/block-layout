<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Api\Layout\BlockCollectionBuilderInterface;
use Siarko\BlockLayout\Api\LayoutProviderInterface;
use Siarko\BlockLayout\Exception\RootBlockNotFound;
use Siarko\BlockLayout\LayoutFactory as LayoutFactory;
use Siarko\DependencyManager\DependencyManager;

class PageRenderer
{

    public const CURRENT_LAYOUT_TYPE_NAME = '$CURRENT_LAYOUT';

    /**
     * @param LayoutFactory $layoutFactory
     * @param DependencyManager $dependencyManager
     * @param BlockCollectionBuilderInterface $blockCollectionBuilder
     * @param LayoutProviderInterface $layoutProvider
     */
    public function __construct(
        private readonly LayoutFactory $layoutFactory,
        private readonly DependencyManager $dependencyManager,
        private readonly BlockCollectionBuilderInterface $blockCollectionBuilder,
        private readonly LayoutProviderInterface $layoutProvider
    )
    {
    }

    /**
     * @return void
     * @throws RootBlockNotFound
     */
    public function render(): void
    {
        $layoutStructure = $this->layoutProvider->getData();
        $blockCollectionBuilder = $this->blockCollectionBuilder->setLayout($layoutStructure);
        $layout = $this->layoutFactory->createNamed(
            blockList: $blockCollectionBuilder->build()
        );
        $this->dependencyManager->bindObject(self::CURRENT_LAYOUT_TYPE_NAME, $layout);
        echo $layout->render();
    }
}
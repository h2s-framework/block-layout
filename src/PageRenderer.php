<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Exception\BlockDataTypeNotSet;
use Siarko\BlockLayout\Exception\LayoutNotExists;
use Siarko\BlockLayout\Exception\RootBlockNotFound;
use Siarko\BlockLayout\LayoutFactory as LayoutFactory;
use Siarko\DependencyManager\DependencyManager;

class PageRenderer
{

    public const CURRENT_LAYOUT_TYPE_NAME = '$CURRENT_LAYOUT';

    /**
     * @param XmlLayoutParser $xmlLayoutParser
     * @param LayoutFactory $layoutFactory
     * @param DependencyManager $dependencyManager
     */
    public function __construct(
        private readonly XmlLayoutParser $xmlLayoutParser,
        private readonly LayoutFactory $layoutFactory,
        private readonly DependencyManager $dependencyManager
    )
    {
    }

    /**
     * @return XmlLayoutParser
     */
    public function getLayoutParser(): XmlLayoutParser
    {
        return $this->xmlLayoutParser;
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
        $layoutData = $this->xmlLayoutParser->loadLayout();
        $layout->setLayoutStructure($layoutData);
        echo $layout->render();
    }
}
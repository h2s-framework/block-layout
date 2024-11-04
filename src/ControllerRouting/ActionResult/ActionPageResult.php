<?php

namespace Siarko\BlockLayout\ControllerRouting\ActionResult;

use Siarko\ActionRouting\ActionResult\AbstractActionResult;
use Siarko\BlockLayout\PageRenderer;
use Siarko\BlockLayout\XmlLayoutParser;

class ActionPageResult extends AbstractActionResult
{

    /**
     * @param PageRenderer $pageRenderer
     */
    public function __construct(
        private readonly PageRenderer $pageRenderer
    )
    {
    }

    /**
     * @return XmlLayoutParser
     */
    public function getLayoutParser(): XmlLayoutParser{
        return $this->pageRenderer->getLayoutProvider()->getLayoutParser();
    }

    /**
     * @param string $layoutId
     * @return void
     */
    public function includeLayout(string $layoutId): void
    {
        $this->getLayoutParser()->enableModifierLayout($layoutId);
    }

    /**
     * Resolves the action result for page rendering
     */
    public function resolve(): void
    {
        $this->pageRenderer->render();
    }
}
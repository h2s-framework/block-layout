<?php

namespace Siarko\BlockLayout\ControllerRouting\ActionResult;

use Siarko\ActionRouting\ActionResult\AbstractActionResult;
use Siarko\BlockLayout\PageRenderer;

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
     * Resolves the action result for page rendering
     */
    public function resolve(): void
    {
        $this->pageRenderer->render();
    }
}
<?php

namespace Siarko\BlockLayout\Template\Tools;

use Siarko\BlockLayout\Argument\BlockArgumentInterface;
use Siarko\Paths\Exception\RootPathNotSet;
use Siarko\UrlService\Processor\UrlProcessorManager;
use Siarko\UrlService\UrlProvider;

class Url implements BlockArgumentInterface
{

    /**
     * @param UrlProcessorManager $urlProcessorManager
     * @param UrlProvider $urlProvider
     */
    public function __construct(
        protected readonly UrlProcessorManager $urlProcessorManager,
        protected readonly UrlProvider $urlProvider
    )
    {
    }

    /**
     * @param string $path
     * @return string
     * @throws RootPathNotSet
     */
    public function get(string $path): string
    {
        return $this->urlProcessorManager->process($path, $this->urlProvider->getCurrentUrl());
    }

}
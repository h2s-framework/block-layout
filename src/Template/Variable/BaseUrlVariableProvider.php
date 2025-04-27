<?php

namespace Siarko\BlockLayout\Template\Variable;

use Siarko\BlockLayout\Api\Template\DataNodeVariableValueProvider;
use Siarko\Paths\Exception\RootPathNotSet;
use Siarko\UrlService\UrlProvider;

class BaseUrlVariableProvider implements DataNodeVariableValueProvider
{

    /**
     * @param UrlProvider $baseUrlProvider
     */
    public function __construct(
        protected readonly UrlProvider $baseUrlProvider
    )
    {
    }

    /**
     * @return string
     * @throws RootPathNotSet
     */
    public function getValue(): string
    {
        return $this->baseUrlProvider->getBaseUrl();
    }

}
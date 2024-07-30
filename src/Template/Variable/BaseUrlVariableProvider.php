<?php

namespace Siarko\BlockLayout\Template\Variable;

use Siarko\UrlService\UrlProvider;

class BaseUrlVariableProvider implements DataNodeVariableValueProvider
{

    public function __construct(
        protected readonly UrlProvider $baseUrlProvider
    )
    {
    }

    public function getValue(): string
    {
        return $this->baseUrlProvider->getBaseUrl();
    }

    public function getName(): string
    {
        return '$BASE_URL';
    }
}
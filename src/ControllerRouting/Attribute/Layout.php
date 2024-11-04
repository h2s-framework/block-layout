<?php

namespace Siarko\BlockLayout\ControllerRouting\Attribute;

use Attribute;
use Siarko\Serialization\Api\Attribute\Serializable;

#[Attribute(Attribute::TARGET_METHOD)]
class Layout
{

    /**
     * @param string|array $layouts
     * @param string|null $baseLayout
     */
    public function __construct(
        #[Serializable] private readonly string|array $layouts,
        #[Serializable] private readonly ?string $baseLayout = null
    )
    {
    }

    /**
     * @return array
     */
    public function getLayouts(): array
    {
        if(is_string($this->layouts)){
            return [$this->layouts];
        }
        return $this->layouts;
    }

    /**
     * @return ?string
     */
    public function getBaseLayout(): ?string
    {
        return $this->baseLayout;
    }
}
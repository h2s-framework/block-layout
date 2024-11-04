<?php

namespace Siarko\BlockLayout\Api;

interface LayoutProviderInterface
{

    /**
     * @return array
     */
    public function getData(): array;

}
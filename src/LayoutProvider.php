<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Api\LayoutProviderInterface;
use Siarko\BlockLayout\Exception\BlockDataTypeNotSet;
use Siarko\BlockLayout\Exception\LayoutNotExists;
use Siarko\CacheFiles\Api\CacheSetInterface;

class LayoutProvider implements LayoutProviderInterface
{

    /**
     * @param XmlLayoutParser $xmlLayoutParser
     * @param CacheSetInterface $configCache
     */
    public function __construct(
        private readonly XmlLayoutParser $xmlLayoutParser,
        private readonly CacheSetInterface $configCache
    )
    {
    }

    /**
     * @return XmlLayoutParser
     */
    public function getLayoutParser(): XmlLayoutParser{
        return $this->xmlLayoutParser;
    }

    /**
     * @return array
     * @throws BlockDataTypeNotSet
     * @throws LayoutNotExists
     */
    public function getData(): array
    {
        $id = $this->xmlLayoutParser->getLayoutSignature();
        if($this->configCache->exists($id)){
            return $this->configCache->get($id);
        }
        $layoutData = $this->xmlLayoutParser->loadLayout();
        $this->configCache->set($id, $layoutData);
        return $layoutData;
    }

}
<?php

namespace Siarko\BlockLayout\ControllerRouting\Plugin;

use Siarko\ActionRouting\ActionManager;
use Siarko\ActionRouting\ActionProvider\RouteData;
use Siarko\BlockLayout\ControllerRouting\Attribute\Layout;
use Siarko\BlockLayout\XmlLayoutParser;
use Siarko\Plugins\Config\Attribute\PluginMethod;
use Siarko\Utils\Code\ClassStructureProvider;

/**
 * @description This plugin applies layout to action methods based on Attribute Layout
 * */
class ActionLayouts
{

    /**
     * @param ClassStructureProvider $classStructureProvider
     * @param XmlLayoutParser $layoutParser
     */
    public function __construct(
        private readonly ClassStructureProvider $classStructureProvider,
        private readonly XmlLayoutParser $layoutParser
    )
    {
    }

    /**
     * @param ActionManager $subject
     * @param RouteData $result
     * @return RouteData
     * @throws \ReflectionException
     */
    #[PluginMethod]
    public function afterGetRouteData(ActionManager $subject, RouteData $result): RouteData {
        $structure = $this->classStructureProvider->get($result->getClassName());
        $method = $structure->getMethod($result->getMethodName());
        $attribute = $method->getNativeReflection()->getAttributes(Layout::class);
        if(!empty($attribute)){
            $this->applyLayout($attribute[0]->newInstance());
        }
        return $result;
    }

    /**
     * @param Layout $attribute
     * @return void
     */
    private function applyLayout(Layout $attribute): void
    {
        if($attribute->getBaseLayout()){
            $this->layoutParser->setBaseLayoutId($attribute->getBaseLayout());
        }
        $this->layoutParser->enableModifierLayouts($attribute->getLayouts());
    }

}
<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Definitions\Builtin\IncludeLayout;
use Siarko\BlockLayout\Definitions\TagParserManager;
use Siarko\BlockLayout\ModifierLayout\Applier;
use Siarko\BlockLayout\Template\DataNodeFactory;
use Siarko\DependencyManager\DependencyManager;
use Siarko\Paths\Provider\Pool\PathProviderPool;
use Siarko\Paths\Api\Provider\Pool\PathProviderPoolInterface;

class XmlLayoutParser
{


    public const PATH_PROVIDER_POOL_TYPE = 'layout';

    public const DEFAULT_LAYOUT_ID = 'default';

    /**
     * @var string[]
     */
    private array $enabledModifierLayouts = [];

    /**
     * @param DependencyManager $dependencyManager
     * @param PathProviderPool $pathProviderPool
     * @param Applier $modifierApplier
     * @param DataNodeFactory $dataNodeFactory
     * @param TagParserManager $parserManager
     * @param string $defaultLayoutId
     */
    public function __construct(
        private readonly PathProviderPoolInterface $pathProviderPool,
        private readonly Applier                   $modifierApplier,
        protected readonly DataNodeFactory         $dataNodeFactory,
        protected readonly TagParserManager        $parserManager,
        protected string                           $defaultLayoutId = self::DEFAULT_LAYOUT_ID
    )
    {
    }

    /**
     * @param string $layoutId
     * @return void
     */
    public function setDefaultLayoutId(string $layoutId): void
    {
        $this->defaultLayoutId = $layoutId;
    }

    /**
     * @return string[]
     */
    public function getEnabledModifierLayouts(): array
    {
        return $this->enabledModifierLayouts;
    }

    /**
     * @param string[] $enabledExtraLayouts
     */
    public function enableModifierLayouts(array $enabledExtraLayouts): void
    {
        array_push($this->enabledModifierLayouts, ...$enabledExtraLayouts);
    }

    /**
     * @param string $layoutId
     */
    public function enableModifierLayout(string $layoutId)
    {
        $this->enableModifierLayouts([$layoutId]);
    }


    /**
     * @return array
     * @throws Exception\BlockDataTypeNotSet
     * @throws Exception\LayoutNotExists
     */
    public function loadLayout(): array
    {
        $modifierLayoutData = [];
        foreach ($this->enabledModifierLayouts as $modifierLayout) {
            $loadedModifier = $this->loadMergedLayouts($modifierLayout);
            foreach ($loadedModifier[IncludeLayout::TYPE] ?? [] as $includeLayoutId => $includeData) {
                $modifierLayoutData[$includeLayoutId] = $this->loadMergedLayouts($includeLayoutId);
            }
            $modifierLayoutData[$modifierLayout] = $loadedModifier;
        }
        return $this->applyLayoutUpdates($this->loadMergedLayouts($this->defaultLayoutId), $modifierLayoutData);
    }

    /**
     * @param array $mainLayout
     * @param array $modifierLayouts
     * @return array
     */
    private function applyLayoutUpdates(array $mainLayout, array $modifierLayouts): array
    {
        foreach ($modifierLayouts as $layout) {
            $mainLayout = $this->modifierApplier->apply($mainLayout, $layout);
        }
        return $mainLayout;
    }

    /**
     * @param string $layoutId
     * @return array
     * @throws Exception\BlockDataTypeNotSet
     * @throws Exception\LayoutNotExists
     */
    protected function loadMergedLayouts(string $layoutId): array
    {
        $layoutData = [];
        foreach ($this->getLayoutPaths($layoutId) as $layoutPath) {
            $layoutData = array_merge_recursive($layoutData, $this->parseFile($layoutPath));
        }
        return $layoutData;
    }

    /**
     * @param string $layoutId
     * @return string[]
     * @throws Exception\LayoutNotExists
     */
    private function getLayoutPaths(string $layoutId): array
    {
        $result = [];
        foreach ($this->pathProviderPool->getProviders(self::PATH_PROVIDER_POOL_TYPE) as $layoutPathProvider) {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $layoutId);
            $path = $layoutPathProvider->getConstructedPath($path) . '.xml';
            if (file_exists($path)) {
                $result[] = $path;
            }
        }
        if (count($result) == 0) {
            throw new \Siarko\BlockLayout\Exception\LayoutNotExists($layoutId);
        }
        return $result;
    }

    /**
     * Parse XML layout file. Return Immediate children data and add all children to referenced $layoutData
     * @param \SimpleXMLElement $simpleXMLElement
     * @param array $layoutData
     * @return array
     */
    private function parseXmlRecurrent(\SimpleXMLElement $simpleXMLElement, array &$layoutData): array
    {
        $childrenData = [];
        foreach ($simpleXMLElement->children() as $child) {
            $parser = $this->parserManager->getParser($child->getName());
            if ($parser) {
                $parsedChild = $parser->shouldParseChildren() ? $this->parseXmlRecurrent($child, $layoutData) : [];
                $tagData = $parser->parse($child, $parsedChild);
                $layoutData = $this->mergeTagData($tagData->asArray(), $layoutData);
                $childrenData = $this->mergeTagData($tagData->asArray(), $childrenData);
                if ($parser->shouldParseChildren() && !empty($parsedChild)) {
                    $layoutData = $this->mergeTagData($parsedChild, $layoutData);
                }
            }
        }
        return $childrenData;
    }

    /**
     * @param array $tagData
     * @param array $layoutData
     * @return array
     */
    private function mergeTagData(array $tagData, array $layoutData)
    {
        foreach ($tagData as $type => $typeData) {
            if (!array_key_exists($type, $layoutData)) {
                $layoutData[$type] = [];
            }
            foreach ($typeData as $id => $data) {
                $layoutData[$type][$id] = $data;
            }
        }
        return $layoutData;
    }

    /**
     * @param string $path
     * @param array $layout
     * @return array
     * @throws Exception\BlockDataTypeNotSet
     */
    private function parseFile(string $path, array $layout = []): array
    {
        return $this->parseXml(simplexml_load_file($path), $layout);
    }

    /**
     * @param \SimpleXMLElement $simpleXMLElement
     * @param array $layoutData
     * @return array
     */
    private function parseXml(\SimpleXMLElement $simpleXMLElement, array $layoutData = []): array
    {
        $this->parseXmlRecurrent($simpleXMLElement, $layoutData);
        return $layoutData;
    }
}
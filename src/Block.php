<?php

namespace Siarko\BlockLayout;

use Siarko\BlockLayout\Argument\BlockArgumentInterface;
use Siarko\BlockLayout\Exception\TemplateFileNotFound;
use Siarko\BlockLayout\Template\ContextualLoader;
use Siarko\BlockLayout\Template\ContextualLoaderFactory;
use Siarko\BlockLayout\Template\DataNode;
use Siarko\BlockLayout\Template\DataNodeFactory;
use Siarko\BlockLayout\Template\RenderResultModifier\RenderResultModifierInterface;
use Siarko\DependencyManager\DependencyManager;
use Siarko\Paths\Api\Provider\Pool\PathProviderPoolInterface;
use Siarko\UrlService\UrlProvider;

class Block
{

    public const PATH_PROVIDER_POOL_TYPE = 'template';
    private const TEMPLATE_EXTENSION = '.phtml';

    private array $templateData = [];

    /**
     * @param string $id
     * @param PathProviderPoolInterface $pathProviderPool
     * @param ContextualLoaderFactory $contextualLoaderFactory
     * @param Layout $layout
     * @param DependencyManager $dependencyManager
     * @param DataNodeFactory $dataNodeFactory
     * @param UrlProvider $baseUrlProvider
     * @param RenderResultModifierInterface[] $renderResultModifiers
     * @param array $childBlockIds
     * @param string|null $template
     * @param array|DataNode $data
     */
    public function __construct(
        protected readonly string                    $id,
        protected readonly PathProviderPoolInterface $pathProviderPool,
        protected readonly ContextualLoaderFactory   $contextualLoaderFactory,
        protected readonly Layout                    $layout,
        protected readonly DependencyManager         $dependencyManager,
        protected readonly DataNodeFactory           $dataNodeFactory,
        protected readonly UrlProvider               $baseUrlProvider,
        protected array                              $renderResultModifiers = [],
        protected array                              $childBlockIds = [],
        protected ?string                            $template = null,
        array|DataNode                               $data = []
    )
    {
        if (!array_key_exists('baseUrl', $data)) {
            $data[] = $this->dataNodeFactory->create([
                'name' => 'baseUrl',
                'type' => 'string',
                'value' => $this->baseUrlProvider->getBaseUrl()
            ]);
        }
        $this->templateData = $this->processTemplateData($data);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $separator
     * @return string
     */
    public function getIdSuffix(string $separator = '.'): string
    {
        $separatorPosition = strrpos($this->getId(), $separator);
        return ($separatorPosition === false) ? $this->getId() : substr($this->getId(), $separatorPosition + 1);
    }

    /**
     * @param string $templateId
     */
    public function setTemplateId(string $templateId): void
    {
        $this->template = $templateId;
    }

    /**
     * @return string|null
     */
    public function getTemplateId(): ?string
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getChildBlockIds(): array
    {
        return $this->childBlockIds;
    }

    /**
     * @param array $childBlockIds
     */
    public function setChildBlockIds(array $childBlockIds): void
    {
        $this->childBlockIds = $childBlockIds;
    }

    /**
     * Called by Layout during Block construction - used by extra blocks
     * @param array $describerData
     */
    public function processAdditionalData(array $describerData)
    {
    }

    /**
     * @param array|DataNode $templateData
     * @return array
     */
    protected function processTemplateData(array|DataNode $templateData): array
    {
        if (!is_array($templateData)) {
            $templateData = [$templateData];
        }
        $result = [];
        foreach ($templateData as $data) {
            $result[$data->getName()] = $data->castValue();
        }
        return $result;
    }

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getTemplateData(?string $name = null): mixed
    {
        if ($name === null) {
            return $this->templateData;
        }
        return $this->templateData[$name];
    }

    /**
     * Use for processing template data in descending classes
     * @param array|DataNode $templateData
     */
    protected function updateTemplateData(array|DataNode $templateData): void
    {
        $this->templateData = array_merge($this->templateData, $this->processTemplateData($templateData));
    }

    /**
     * @return ContextualLoader
     */
    protected function prepareTemplate(): ContextualLoader
    {
        $templateLoader = $this->contextualLoaderFactory->create();
        $templateLoader->__setData($this->templateData);
        $this->registerTemplateMethods($templateLoader);
        return $templateLoader;
    }

    /**
     * @param ContextualLoader $contextualLoader
     */
    protected function registerTemplateMethods(ContextualLoader $contextualLoader): void
    {
        $contextualLoader->registerCallHandler('getChildHtml', function ($id) {
            $block = $this->getChild($id);
            return ($block instanceof Block ? $block->render() : '');
        });
        $contextualLoader->registerCallHandler('getChild', function ($id) {
            return $this->getChild($id);
        });
        $contextualLoader->registerCallHandler('getChildren', function () {
            $result = [];
            foreach ($this->getChildBlockIds() as $childBlockId) {
                $block = $this->getChild($childBlockId);
                if ($block instanceof Block) {
                    $result[$childBlockId] = $block;
                }
            }
            return $result;
        });
        $contextualLoader->registerCallHandler('getChildrenHtml', function () {
            $result = '';
            foreach ($this->getChildBlockIds() as $childBlockId) {
                $block = $this->getChild($childBlockId);
                if ($block instanceof Block) {
                    $result .= $block->render();
                }
            }
            return $result;
        });
    }

    /**
     * @param string $id
     * @return Block|null
     */
    public function getChild(string $id): ?Block
    {
        return $this->layout->getBlock($id);
    }

    /**
     * Filters out blocks that are returned as null
     * @param array $ids
     * @return array
     */
    public function getChildren(array $ids = []): array
    {
        if (count($ids) == 0) {
            $ids = $this->getChildBlockIds();
        }
        $result = [];
        foreach ($ids as $id) {
            $block = $this->getChild($id);
            if ($block instanceof Block) {
                $result[$id] = $block;
            }
        }
        return $result;
    }

    /**
     * @return string
     * @throws TemplateFileNotFound
     */
    public function getChildrenHtml(): string
    {
        return implode('', array_map(function (Block $block) {
                return $block->render();
            }, $this->getChildren())
        );
    }

    /**
     * Bind all arguments which implement BlockArgumentInterface to $this block
     */
    protected function bindArgumentObjects()
    {
        foreach ($this->templateData as $argument) {
            if ($argument instanceof BlockArgumentInterface) {
                $argument->setBlock($this);
            }
        }
    }

    /**
     * @param ContextualLoader $contextualLoader
     * @return string
     * @throws TemplateFileNotFound
     */
    protected function renderTemplate(ContextualLoader $contextualLoader): string
    {
        try {
            $templatePath = $this->findTemplateFile($this->getTemplateId());
            $contextualLoader->setPath($templatePath);
            $this->bindArgumentObjects(); //bind arguments which implement BlockArgumentInterface to $this
            return $contextualLoader->render();
        } catch (Exception\TemplateFileNotFound $e) {
            throw new TemplateFileNotFound("Template file not found for " . get_class($this) . "{" . $this->getId() . "}[" . $this->getTemplateId() . "]");
        }
    }

    /**
     * @param string|null $templateId
     * @return string
     * @throws TemplateFileNotFound
     */
    protected function findTemplateFile(?string $templateId): string
    {
        foreach ($this->pathProviderPool->getProviders(self::PATH_PROVIDER_POOL_TYPE) as $templatePathProvider) {
            $path = $templatePathProvider->getConstructedPath($templateId) . self::TEMPLATE_EXTENSION;
            if (!empty($path) && file_exists($path)) {
                return $path;
            }
        }
        throw new TemplateFileNotFound($templateId);
    }

    /**
     * @return string
     * @throws TemplateFileNotFound
     */
    public function render(): string
    {
        if ($this->getTemplateId() == null) {
            $renderResult = $this->getChildrenHtml();
        } else {
            $template = $this->prepareTemplate();
            $renderResult = $this->renderTemplate($template);
        }
        foreach ($this->renderResultModifiers as $renderResultModifier) {
            $renderResult = $renderResultModifier->apply($renderResult, $this);
        }
        return $renderResult;
    }

}
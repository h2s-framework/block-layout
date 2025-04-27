<?php

namespace Siarko\BlockLayout\Blocks;

use Siarko\BlockLayout\Api\Layout\Definitions\BlockInterface;
use Siarko\BlockLayout\Api\Template\RenderResultModifierInterface;
use Siarko\BlockLayout\Argument\BlockAwareArgumentInterface;
use Siarko\BlockLayout\Exception\TemplateFileNotFound;
use Siarko\BlockLayout\Layout;
use Siarko\BlockLayout\Template\ContextualLoader;
use Siarko\BlockLayout\Template\ContextualLoaderFactory;
use Siarko\BlockLayout\Template\DataNode;
use Siarko\BlockLayout\Template\DataNodeFactory;
use Siarko\Paths\Api\Provider\Pool\PathProviderPoolInterface;
use Siarko\UrlService\UrlProvider;
use Siarko\Utils\Exceptions\TypeCastException;

class Block implements BlockInterface
{

    public const PATH_PROVIDER_POOL_TYPE = 'template';
    private const TEMPLATE_EXTENSION = '.phtml';

    private array $templateData = [];

    /**
     * @param string $id
     * @param PathProviderPoolInterface $pathProviderPool
     * @param ContextualLoaderFactory $contextualLoaderFactory
     * @param Layout $layout
     * @param DataNodeFactory $dataNodeFactory
     * @param UrlProvider $baseUrlProvider
     * @param RenderResultModifierInterface[] $renderResultModifiers
     * @param array $childrenIds
     * @param string|null $template
     * @param DataNode[] $data
     * @throws TypeCastException
     */
    public function __construct(
        protected readonly string                    $id,
        protected readonly PathProviderPoolInterface $pathProviderPool,
        protected readonly ContextualLoaderFactory   $contextualLoaderFactory,
        protected readonly Layout                    $layout,
        protected readonly DataNodeFactory           $dataNodeFactory,
        protected readonly UrlProvider               $baseUrlProvider,
        protected array                              $renderResultModifiers = [],
        protected array                              $childrenIds = [],
        protected ?string                            $template = null,
        array                                        $data = []
    )
    {
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
    public function getChildrenIds(): array
    {
        return $this->childrenIds;
    }

    /**
     * @param array $childrenIds
     */
    public function setChildrenIds(array $childrenIds): void
    {
        $this->childrenIds = $childrenIds;
    }

    /**
     * Called by Layout during Block construction - used by extra blocks
     * @param array $describerData
     */
    public function processAdditionalData(array $describerData)
    {
    }

    /**
     * @param DataNode[] $templateData
     * @return array
     * @throws TypeCastException
     */
    protected function processTemplateData(array $templateData): array
    {
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
     * @param DataNode[] $templateData
     * @throws TypeCastException
     */
    protected function updateTemplateData(array $templateData): void
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
            foreach ($this->getChildrenIds() as $childBlockId) {
                $block = $this->getChild($childBlockId);
                if ($block instanceof Block) {
                    $result[$childBlockId] = $block;
                }
            }
            return $result;
        });
        $contextualLoader->registerCallHandler('getChildrenHtml', function () {
            $result = '';
            foreach ($this->getChildrenIds() as $childBlockId) {
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
        if(!in_array($id, $this->getChildrenIds())){
            $id = $this->getId() . '.' . $id;
        }
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
            $ids = $this->getChildrenIds();
        }
        $result = [];
        foreach ($ids as $id) {
            $block = $this->layout->getBlock($id);
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
     * Bind all arguments which implement BlockAwareArgumentInterface to $this block
     */
    protected function bindArgumentObjects()
    {
        foreach ($this->templateData as $argument) {
            if ($argument instanceof BlockAwareArgumentInterface) {
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
            $this->bindArgumentObjects(); //bind arguments which implements BlockArgumentInterface to $this
            return $contextualLoader->render();
        } catch (TemplateFileNotFound $e) {
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
            $renderResult = $renderResultModifier->apply($renderResult, $this, $this->layout);
        }
        return $renderResult;
    }

}
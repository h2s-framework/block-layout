<?php

namespace Siarko\BlockLayout\LayoutSorting;

use Siarko\BlockLayout\Api\LayoutSorting\ProcessorInterface;

class LoadedLayoutSorter
{

    /**
     * @param array $layouts
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        private array                 $layouts = [],
        private readonly array        $processors = []
    )
    {
    }

    /**
     * @param string $id
     * @param array $layoutData
     * @return void
     */
    public function addLayout(string $id, array $layoutData): void {
        $this->layouts[$id] = $layoutData;
        $this->layouts = $this->runProcessors($this->layouts, $id, $layoutData);
    }

    /**
     * @return array
     */
    public function getLayouts(): array {
        return $this->layouts;
    }

    /**
     * @param array $layouts
     * @param string $id
     * @param array $layoutData
     * @return array
     */
    private function runProcessors(array $layouts, string $id, array $layoutData): array
    {
        foreach ($this->processors as $modifier) {
            $layouts = $modifier->process($layouts, $id, $layoutData);
        }
        return $layouts;

    }

}
<?php

namespace Siarko\BlockLayout\Api\Layout;

interface ActiveLayoutListInterface extends \Iterator
{

    public const BASE_LAYOUT = 'default';

    /**
     * Set base layout id
     * @param string $layoutId
     * @return void
     */
    public function setBaseLayoutId(string $layoutId): void;

    /**
     * Return base layout id
     * @return string
     */
    public function getBaseLayoutId(): string;

    /**
     * Enable layout so that it will be used in rendering
     * @param string $layoutId
     * @return void
     */
    public function enableLayout(string $layoutId): void;

    /**
     * Enable multiple layouts so that they will be used in rendering
     * @param array $layoutIds
     * @return void
     */
    public function enableLayouts(array $layoutIds): void;

    /**
     * Disable layout so that it will not be used in rendering
     * @param string $layoutId
     * @return void
     */
    public function disableLayout(string $layoutId): void;

    /**
     * Disable multiple layouts so that they will not be used in rendering
     * @param array $layoutIds if empty, all layouts will be disabled
     * @return void
     */
    public function disableLayouts(array $layoutIds): void;

    /**
     * Return list of active layouts
     * @param bool $withBase
     * @return string[]
     */
    public function getActiveLayouts(bool $withBase = false): array;
}
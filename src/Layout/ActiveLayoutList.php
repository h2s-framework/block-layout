<?php

namespace Siarko\BlockLayout\Layout;

use Siarko\BlockLayout\Api\Layout\ActiveLayoutListInterface;

class ActiveLayoutList implements ActiveLayoutListInterface
{

    /**
     * @param string $baseLayout
     * @param array $layouts
     */
    public function __construct(
        private string $baseLayout = self::BASE_LAYOUT,
        private array $layouts = []
    )
    {
    }

    /**
     * Set base layout id
     * @param string $layoutId
     * @return void
     */
    public function setBaseLayoutId(string $layoutId): void
    {
        $this->baseLayout = $layoutId;
    }

    /**
     * Return base layout id
     * @return string
     */
    public function getBaseLayoutId(): string
    {
        return $this->baseLayout;
    }


    /**
     * Enable layout so that it will be used in rendering
     * @param string $layoutId
     * @return void
     */
    public function enableLayout(string $layoutId): void
    {
        $this->layouts[] = $layoutId;
    }

    /**
     * Enable multiple layouts so that they will be used in rendering
     * @param array $layoutIds
     * @return void
     */
    public function enableLayouts(array $layoutIds): void
    {
        $this->layouts = array_merge($this->layouts, $layoutIds);
    }

    /**
     * Disable layout so that it will not be used in rendering
     * @param string $layoutId
     * @return void
     */
    public function disableLayout(string $layoutId): void
    {
        $this->layouts = array_filter($this->layouts, fn($id) => $id !== $layoutId);
    }

    /**
     * Disable multiple layouts so that they will not be used in rendering
     * @param array $layoutIds if empty, all layouts will be disabled
     * @return void
     */
    public function disableLayouts(array $layoutIds): void
    {
        if(empty($layoutIds)){
            $this->layouts = [];
        }else{
            $this->layouts = array_filter($this->layouts, fn($id) => !in_array($id, $layoutIds));
        }
    }

    /**
     * Return list of active layouts
     * @param bool $withBase
     * @return string[]
     */
    public function getActiveLayouts(bool $withBase = false): array
    {
        if($withBase){
            return [$this->baseLayout, ...$this->layouts];
        }
        return $this->layouts;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current(): mixed
    {
        return current($this->layouts);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        next($this->layouts);
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return TKey|null TKey on success, or null on failure.
     */
    public function key(): mixed
    {
        return key($this->layouts);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return key($this->layouts) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        reset($this->layouts);
    }
}
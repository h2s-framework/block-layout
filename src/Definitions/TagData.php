<?php

namespace Siarko\BlockLayout\Definitions;

class TagData
{

    public const ID = 'id';
    public const CHILDREN = 'children';

    /**
     * @param string $type
     * @param string $id
     * @param array $children
     * @param array $extraData
     */
    public function __construct(
        private string $type,
        private string $id,
        private array  $children = [],
        private array  $extraData = []
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    /**
     * @param string $type
     * @param string $id
     */
    public function addChild(string $type, string $id): void
    {
        if (!array_key_exists($type, $this->children)) {
            $this->children[$type] = [];
        }
        $this->children[$type][] = $id;
    }

    /**
     * @param string $type
     * @param array $ids
     * @return void
     */
    public function addChildren(string $type, array $ids): void
    {
        foreach ($ids as $id) {
            $this->addChild($type, $id);
        }
    }

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }

    /**
     * @param array $extraData
     */
    public function setExtraData(array $extraData): void
    {
        $this->extraData = $extraData;
    }

    public function addExtraData(string $key, mixed $data)
    {
        $this->extraData[$key] = $data;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [$this->getType() => [$this->getId() => array_merge([
            self::ID => $this->getId(),
            self::CHILDREN => $this->getChildren()
        ], $this->getExtraData())]];
    }
}
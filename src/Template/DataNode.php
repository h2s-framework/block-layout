<?php

namespace Siarko\BlockLayout\Template;

use Siarko\DependencyManager\DependencyManager;
use Siarko\Serialization\Api\Attribute\Serializable;
use Siarko\Utils\Exceptions\TypeCastException;
use Siarko\Utils\TypeManager;

class DataNode
{

    /**
     * @param string $name
     * @param string $type
     * @param string $value
     * @param DependencyManager $dependencyManager
     * @param DataNodeVariableParser $nodeVariableParser
     * @param TypeManager $typeManager
     */
    public function __construct(
        #[Serializable] protected string $name,
        #[Serializable] protected string $type,
        #[Serializable] protected string $value,
        protected DependencyManager $dependencyManager,
        protected readonly DataNodeVariableParser $nodeVariableParser,
        protected readonly TypeManager $typeManager
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getParsedValue(): string
    {
        return $this->nodeVariableParser->parse($this->getName(), $this->getValue());
    }

    /**
     * @return mixed
     * @throws TypeCastException
     */
    public function castValue(): mixed
    {
        return $this->typeManager->cast($this->getType(), $this->getParsedValue());
    }

    /**
     * @param mixed $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }



}
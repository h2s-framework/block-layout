<?php

namespace Siarko\BlockLayout\ModifierLayout\Type;

use Siarko\BlockLayout\Exception\UnknownBlockModifierRequested;
use Siarko\DependencyManager\DependencyManager;

class ModifierTypeProvider
{

    /**
     * @param IModifierType[] $types
     */
    public function __construct(
        private readonly array $types = [],
    )
    {
    }

    /**
     * @param string $name
     * @return bool
     */
    public function typeExists(string $name): bool{
        return array_key_exists($name, $this->types);
    }
    /**
     * @param string $name
     * @return IModifierType|null
     */
    public function getType(string $name): ?IModifierType
    {
        if(array_key_exists($name, $this->types)){
            return $this->types[$name];
        }
        throw new UnknownBlockModifierRequested($name);
    }

}
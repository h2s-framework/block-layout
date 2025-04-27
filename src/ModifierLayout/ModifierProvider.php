<?php

namespace Siarko\BlockLayout\ModifierLayout;

use Siarko\BlockLayout\Api\Layout\Modify\ModifierInterface;
use Siarko\BlockLayout\Exception\UnknownBlockModifierRequested;

class ModifierProvider
{

    /**
     * @param ModifierInterface[] $modifiers
     */
    public function __construct(
        private readonly array $modifiers = [],
    )
    {
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsForType(string $name): bool{
        return array_key_exists($name, $this->modifiers);
    }

    /**
     * @param string $name
     * @return ModifierInterface|null
     * @throws UnknownBlockModifierRequested
     */
    public function getModifier(string $name): ?ModifierInterface
    {
        if(array_key_exists($name, $this->modifiers)){
            return $this->modifiers[$name];
        }
        throw new UnknownBlockModifierRequested($name);
    }

}
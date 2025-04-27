<?php

namespace Siarko\BlockLayout\Api\Layout\Modify;

interface ModifierInterface
{

    public function getPriority(): int;

    public function apply(array $mainLayout, array $modifier): array;

}
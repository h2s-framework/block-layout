<?php

declare(strict_types=1);

namespace Siarko\BlockLayout\Api\Template;

interface CallHandlerInterface
{

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function handle(string $name, array $arguments = []): mixed;
}
<?php

namespace Siarko\BlockLayout\Api\Layout;

interface BlockCollectionBuilderInterface
{

    public const ROOT_BLOCK_ID = 'root';

    public function setLayout(array $layout): static;

    public function build(): array;

}
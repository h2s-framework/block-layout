<?php

namespace Siarko\BlockLayout\Definitions;

interface TagParserInterface
{

    public function parse(\SimpleXMLElement $element, array $childData = []): TagData;

    public function shouldParseChildren(): bool;

}
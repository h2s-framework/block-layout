<?php

namespace Siarko\BlockLayout\Config\Provider;

use Siarko\ConfigFiles\Api\Provider\LookupScopeComparatorInterface;
use Siarko\Files\Lookup\Result;

class LookupScopeComparator implements LookupScopeComparatorInterface
{

    public function compare(string $targetScope, Result $lookupResult): bool
    {
        $baseDir = $lookupResult->getLookupDirectory();
        $filePath = $lookupResult->getFile()->getPath();
        $relativePath = str_replace($baseDir, '', $filePath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $relativePath);
        return $pathParts[0] === $targetScope;
    }
}
<?php

namespace Siarko\BlockLayout\Exception;

use Exception;
use Throwable;

class BlockDataTypeNotSet extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Type attribute not found for ".$message, $code, $previous);
    }


}
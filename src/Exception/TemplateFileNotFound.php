<?php

namespace Siarko\BlockLayout\Exception;

use Siarko\BlockLayout\Block;
use Throwable;

class TemplateFileNotFound extends \Exception
{
    public function __construct($file = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Template File not found for path: ".$file, $code, $previous);
    }


}
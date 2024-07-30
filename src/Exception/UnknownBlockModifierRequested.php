<?php

namespace Siarko\BlockLayout\Exception;

use Throwable;

class UnknownBlockModifierRequested extends \Exception
{
    public function __construct($name = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Unknown layout modifier type requested: ".$name, $code, $previous);
    }


}
<?php

namespace Bitmap\Exceptions;

use Exception;

class MapperException extends Exception
{
    protected $class;

    public function __construct($message, $class = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->class = $class;
    }

}
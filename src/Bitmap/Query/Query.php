<?php

namespace Bitmap\Query;

use Bitmap\Mapper;

abstract class Query
{
    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public abstract function sql();
}
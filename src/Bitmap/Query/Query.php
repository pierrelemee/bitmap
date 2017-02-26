<?php

namespace Bitmap\Query;

use Bitmap\Mapper;
use PDO;

abstract class Query
{
    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public abstract function execute(PDO $connection);

    /**
     * @return string
     */
    public abstract function sql();
}
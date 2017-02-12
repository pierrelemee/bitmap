<?php

namespace Bitmap\Query;

use Bitmap\Mapper;
use Bitmap\Strategies\GroupStrategy;

class RawSelectQuery extends RetrieveQuery
{
    /**
     * @var string $sql
     */
    protected $sql;

    /**
     * RawSelectQuery constructor.
     * @param Mapper $mapper
     * @param string $sql
     */
    public function __construct(Mapper $mapper, $sql)
    {
        parent::__construct($mapper);
        $this->sql = $sql;
    }

    protected function fieldMappingStrategy()
    {
        return GroupStrategy::of($this->mapper);
    }


    public function sql()
    {
        return $this->sql;
    }
}
<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Strategies\PrefixStrategy;
use Bitmap\Mapper;

class Select extends RetrieveQuery
{
    protected $where;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->where = [];
    }

    protected function fieldMappingStrategy()
    {
        return PrefixStrategy::of($this->mapper);
    }

    public static function fromClass($class)
    {
        return new Select(Bitmap::getMapper(is_object($class) ? get_class($class) : $class));
    }

    public function where($field, $operation, $value)
    {
        $this->where[] = sprintf(
            "`%s`.`%s` %s %s",
            $this->mapper->getTable(),
            $this->mapper->getField($field)->getColumn(),
            $operation,
            $this->mapper->getField($field)->getTransformer()->fromObject($value)
        );

        return $this;
    }

    public function sql()
    {
        return sprintf("select %s from %s %s",
            implode(", ", $this->mapper->fieldNames()),
            $this->mapper->getTable() . (implode(" ", $this->mapper->associationNames())),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : ""
        );
    }
}
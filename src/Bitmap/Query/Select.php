<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Mapper;
use Bitmap\Entity;
use PDO;

class Select extends Query
{
    protected $where;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->where = [];
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

    /**
     * @param null $connection
     * @with array
     *
     * @return Entity|null
     */
    public function one($connection = null, $with = [])
    {
        $stmt = Bitmap::connection($connection)->query($this->sql(), PDO::FETCH_ASSOC);

        if (false !== $stmt) {
            if (false !== ($data = $stmt->fetch())) {
                return $this->mapper->load($data, $with);
            }
        }
        return null;
    }

    public function all($connection = null, $with = [])
    {
        $stmt = Bitmap::connection($connection)->query($this->sql(), PDO::FETCH_ASSOC);
        $entities = [];

        if (false !== $stmt) {
            while (false !== ($data = $stmt->fetch())) {
                $entities[] = $this->mapper->load($data, $with);
            }
        }
        return $entities;
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
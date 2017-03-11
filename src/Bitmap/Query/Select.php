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
        $this->strategy = new PrefixStrategy();
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

    protected function joinClauses($mapper = null)
    {
        $joins = [];
        $mapper = $mapper ? : $this->mapper;
        foreach ($mapper->associations() as $association) {
            $joins = array_merge($joins, $association->joinClauses($mapper));
        }
        foreach ($mapper->associations() as $association) {
            $joins = array_merge($joins, $this->joinClauses($association->getMapper()));
        }

        return $joins;
    }

    /**
     * @param Mapper $mapper
     *
     * @return array
     */
    protected function fields($mapper = null)
    {
        $mapper = $mapper ? : $this->mapper;
        $fields = [];
        foreach ($mapper->getFields() as $field) {
            $fields[] = "`{$mapper->getTable()}`.`{$field->getColumn()}` as `{$this->strategy->getFieldLabel($mapper, $field)}`";
        }

        foreach ($mapper->associations() as $association) {
            $fields = array_merge($fields, $this->fields($association->getMapper()));
        }

        return $fields;
    }

    public function sql()
    {
        return sprintf("select %s from %s %s",
            implode(", ", $this->fields()),
            $this->mapper->getTable() . (implode("", $this->joinClauses())),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : ""
        );
    }
}
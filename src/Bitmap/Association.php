<?php

namespace Bitmap;

abstract class Association
{
    protected $name;
    protected $class;
    protected $column;

    public function __construct($name, $class, $column)
    {
        $this->name = $name;
        $this->class = $class;
        $this->column = $column;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Mapper
     */
    public function getMapper()
    {
        return Bitmap::current()->getMapper($this->class);
    }

    /**
     * Indicates whether the association requires a value in a column of the source entity's table
     *
     * @return boolean
     */
    public abstract function hasLocalValue();

    /**
     * Return the list of join clauses from the class managed by the mapper $left
     *
     * @param Mapper $mapper
     * @param integer $depth
     *
     * @return string[]
     */
    public abstract function joinClauses(Mapper $mapper, $depth);

    protected function joinClause($tableFrom, $columnFrom, $tableTo, $columnTo, $aliasTo = null)
    {
        $alias = $aliasTo ? : $tableTo;
        return " left join `{$tableTo}` {$alias} on `{$tableFrom}`.`{$columnFrom}` = `{$alias}`.`{$columnTo}`";
    }

    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public abstract function get(Entity $entity);

    /**
     * @param Entity $entity
     *
     * @return Entity[]
     */
    public abstract function getAll(Entity $entity);

    public abstract function set($value, Entity $entity);
}
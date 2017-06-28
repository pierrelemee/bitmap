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
     * @param string $name
     *
     * @return string[]
     */
    public abstract function joinClauses(Mapper $mapper, $name);

    protected function joinClause($tableFrom, $columnFrom, $tableTo, $columnTo)
    {
        return " left join `{$tableTo}` {$tableTo} on `{$tableFrom}`.`{$columnFrom}` = `{$tableTo}`.`{$columnTo}`";
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
<?php

namespace Bitmap;

abstract class Association
{
    protected $name;
    protected $mapper;
    protected $right;

    public function __construct($name, Mapper $mapper, $right)
    {
        $this->name = $name;
        $this->mapper = $mapper;
        $this->right = $right;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Mapper
     */
    public function getMapper()
    {
        return $this->mapper;
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
     * @param Mapper $left
     * @return string[]
     */
    public abstract function joinClauses(Mapper $left);

    protected function joinClause($tableFrom, $columnFrom, $tableTo, $columnTo)
    {
        return " inner join `{$tableTo}` on `{$tableFrom}`.`{$columnFrom}` = `{$tableTo}`.`{$columnTo}`";
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
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

    public abstract function set(ResultSet $result, Entity $entity);
}
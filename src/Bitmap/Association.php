<?php

namespace Bitmap;

abstract class Association
{
    protected $name;
    protected $mapper;
    protected $target;

    public function __construct($name, Mapper $mapper, $target)
    {
        $this->name = $name;
        $this->mapper = $mapper;
        $this->target = $target;
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

    public abstract function joinClause(Mapper $left);

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public abstract function get(Entity $entity);

    public abstract function set(ResultSet $result, Entity $entity);
}
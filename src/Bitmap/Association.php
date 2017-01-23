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

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    public function set(Entity $entity, array $values)
    {
        $this->setValue($entity, $this->mapper->load($values));
    }

    protected abstract function setValue(Entity $entity, Entity $associated);
}
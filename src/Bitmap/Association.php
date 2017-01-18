<?php

namespace Bitmap;

abstract class Association
{
    protected $name;
    protected $mapper;

    public function __construct($name, Mapper $mapper)
    {
        $this->name = $name;
        $this->mapper = $mapper;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function set(Entity $entity, array $values)
    {
        $this->setValue($entity, $this->mapper->load($values));
    }

    protected abstract function setValue(Entity $entity, Entity $associated);

}
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

    /**
     * @param Entity $entity
     *
     * @return Entity
     */
    public function get(Entity $entity)
    {
        return $this->getEntity($entity);
    }

    /**
     * @param Entity $entity
     */
    protected abstract function getEntity(Entity $entity);

    public function set(Entity $entity, array $values, FieldMappingStrategy $strategy)
    {
        $this->setEntity($entity, $this->mapper->load($values, $strategy));
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);
}
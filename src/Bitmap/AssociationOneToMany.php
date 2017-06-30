<?php

namespace Bitmap;

abstract class AssociationOneToMany extends Association
{
    public function joinClauses(Mapper $mapper, $name)
    {
        return [
            $this->joinClause($mapper->getTable(), $mapper->getPrimary()->getColumn(), $this->getMapper()->getTable(), $name, $this->column)
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return Entity[]
     */
    public function get(Entity $entity)
    {
        return $this->getEntities($entity);
    }

    public function getAll(Entity $entity)
    {
        return $this->get($entity) ? : [];
    }

    public function hasLocalValue()
    {
        return false;
    }

    /**
     * @param Entity $entity
     *
     * @return Entity[]
     */
    protected abstract function getEntities(Entity $entity);

    public function set($value, Entity $entity)
    {
        $this->setEntities($entity, $value);
    }

    /**
     * @param Entity $entity
     * @param array $entities
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


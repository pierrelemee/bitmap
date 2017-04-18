<?php

namespace Bitmap;

abstract class AssociationOneToMany extends Association
{
    public function joinClauses($name, $index)
    {
        return [
            $this->joinClause($name, $this->name, $this->getMapper()->getTable(), $this->right)
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

    public function set(ResultSet $result, Entity $entity, $with = [], $depth = 0)
    {
        $this->setEntities($entity, $this->getMapper()->loadAll($result));
    }

    /**
     * @param Entity $entity
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


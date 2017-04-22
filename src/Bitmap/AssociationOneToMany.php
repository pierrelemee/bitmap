<?php

namespace Bitmap;

abstract class AssociationOneToMany extends Association
{
    public function joinClauses(Mapper $left)
    {
        return [
            $this->joinClause($left->getTable(), $this->name, $this->mapper->getTable(), $this->right)
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

    public function set(ResultSet $result, Entity $entity)
    {
        $this->setEntities($entity, $this->mapper->loadAll($result));
    }

    /**
     * @param Entity $entity
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


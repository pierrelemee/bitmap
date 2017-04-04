<?php

namespace Bitmap;

abstract class AssociationOne extends Association
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
     * @return Entity
     */
    public function get(Entity $entity)
    {
        return $this->getEntity($entity);
    }

    public function getAll(Entity $entity)
    {
        return [
            $this->get($entity)
        ];
    }

    /**
     * @param Entity $entity
     */
    protected abstract function getEntity(Entity $entity);

    public function set(ResultSet $result, Entity $entity)
    {
        $this->setEntity($entity, $this->mapper->loadOne($result));
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);
}
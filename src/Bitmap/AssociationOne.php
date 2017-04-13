<?php

namespace Bitmap;

abstract class AssociationOne extends Association
{
    public function joinClauses(Mapper $left)
    {
        return [
            $this->joinClause($left->getTable(), $this->name, $this->getMapper()->getTable(), $this->right)
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
        return ($value = $this->get($entity)) !== null ? [$value] : [];
    }

    public function hasLocalValue()
    {
        return true;
    }

    /**
     * @param Entity $entity
     */
    protected abstract function getEntity(Entity $entity);

    public function set(ResultSet $result, Entity $entity)
    {
        $this->setEntity($entity, $this->getMapper()->loadOne($result));
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);
}
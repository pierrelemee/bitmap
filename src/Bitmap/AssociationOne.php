<?php

namespace Bitmap;

abstract class AssociationOne extends Association
{
    public function joinClauses($name, $index)
    {
        return [
            $this->joinClause($name, $this->name, $this->getMapper()->getTable(), $this->right, $index > 0 ? $this->getMapper()->getTable() . $index : '')
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

    public function set(ResultSet $result, Entity $entity, $with = [], $depth = 0)
    {
        $this->setEntity($entity, $this->getMapper()->loadOne($result, $with, $depth));
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);
}
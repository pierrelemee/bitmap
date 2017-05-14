<?php

namespace Bitmap;



abstract class AssociationOne extends Association
{
    public function joinClauses($name, $depth)
    {
        return [
            $this->joinClause($name, $this->column, $this->getMapper()->getTable(), $this->getMapper()->getPrimary()->getColumn(), $this->getMapper()->getTable() . ($depth > 0 ?  $depth : ''))
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

    public function set($value, Entity $entity)
    {
        $this->setEntity($entity, $value);
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);
}
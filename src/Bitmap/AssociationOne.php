<?php

namespace Bitmap;



use Bitmap\Query\Clauses\Join;

abstract class AssociationOne extends Association
{
    public function joinClauses(Mapper $mapper, $alias)
    {
        return [
            Join::create()
            ->setFromTable($mapper->getTable())
            ->setFromColumn($this->column)
            ->setToTable($this->getMapper()->getTable())
            ->setToTableAlias($alias)
            ->setToColumn($this->getMapper()->getPrimary()->getColumn())
        ];
    }

    protected function getDefaultAutoload()
    {
        return true;
    }

    protected function getDefaultAutosave()
    {
        return true;
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
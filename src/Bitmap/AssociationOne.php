<?php

namespace Bitmap;

abstract class AssociationOne extends Association
{
    public function joinClause(Mapper $left)
    {
        return sprintf(
            " inner join `%s` on `%s`.`%s` = `%s`.`%s`",
            $this->mapper->getTable(),
            $left->getTable(),
            $this->name,
            $this->mapper->getTable(),
            $this->target
        );
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

    public function set(ResultSet $result, Entity $entity)
    {
        $this->setEntity($entity, $this->mapper->loadOne($result));
    }

    protected abstract function setEntity(Entity $entity, Entity $associated);

}
<?php

namespace Bitmap;

abstract class AssociationMultiple extends Association
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
     * @return Entity[]
     */
    public function get(Entity $entity)
    {
        return $this->getEntities($entity);
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


<?php

namespace Bitmap;

abstract class AssociationManyToMany extends Association
{
    protected $through;
    protected $sourceReference;
    protected $targetReference;
    
    public function __construct($name, Mapper $mapper, $target, $through, $sourceReference, $targetReference)
    {
        parent::__construct($name, $mapper, $target);
        $this->through = $through;
        $this->sourceReference = $sourceReference;
        $this->targetReference = $targetReference;
    }

    public function joinClause(Mapper $left)
    {
        echo __METHOD__;
        return sprintf(
            " inner join `%s` on `%s`.`%s` = `%s`.`%s` inner join `%s` on `%s`.`%s` = `%s`.`%s` ",
            $this->through,
            $left->getTable(),
            $this->name,
            $this->through,
            $this->sourceReference,
            // 2nd table
            $this->mapper->getTable(),
            $this->through,
            $this->targetReference,
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


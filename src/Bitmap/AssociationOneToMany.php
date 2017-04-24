<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;

abstract class AssociationOneToMany extends Association
{
    /**
     * @var string destination table column
     */
    protected $target;

    public function __construct($name, $class, $target)
    {
        parent::__construct($name, $class);
        $this->target = $target;
    }

    public function joinClauses($name, $depth)
    {
        return [
            $this->joinClause($name, $this->name, $this->getMapper()->getTable(), $this->target)
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

    public function set(ResultSet $result, Entity $entity, Context $context, $depth = 0)
    {
        $this->setEntities($entity, $this->getMapper()->loadAll($result, $context, $depth));
    }

    /**
     * @param Entity $entity
     * @param array $entities
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


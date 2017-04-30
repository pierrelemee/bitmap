<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;

abstract class AssociationManyToMany extends Association
{
    /**
     * @var $via
     */
    protected $via;
    protected $viaSourceColumn;
    protected $viaTargetColumn;
    
    public function __construct($name, $class, $column, $via, $viaSourceColumn, $viaTargetColumn)
    {
        parent::__construct($name, $class, $column);
        $this->via = $via;
        $this->viaSourceColumn = $viaSourceColumn;
        $this->viaTargetColumn = $viaTargetColumn;
    }

    public function joinClauses($name, $depth)
    {
        return [
            $this->joinClause($name, $this->column, $this->via, $this->viaSourceColumn),
            $this->joinClause($this->via, $this->viaTargetColumn, $this->getMapper()->getTable(), $this->column)
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
        return $this->get($entity);
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

    public function set(ResultSet $result, Entity $entity, Context $context)
    {
        $this->setEntities($entity, $this->getMapper()->loadAll($result, $context));
    }

    /**
     * @param Entity $entity
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


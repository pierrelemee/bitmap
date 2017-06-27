<?php

namespace Bitmap;



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

    public function joinClauses(Mapper $mapper, $depth)
    {
        return [
            $this->joinClause($mapper->getTable(), $this->column, $this->via, $this->viaSourceColumn, $this->getMapper()->getTable() . ($depth > 0 ?  $depth : '')),
            $this->joinClause($this->via, $this->viaTargetColumn, $this->getMapper()->getTable(), $this->column, $this->getMapper()->getTable() . ($depth > 0 ?  $depth : ''))
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

    public function set($value, Entity $entity)
    {
        $this->setEntities($entity, $value);
    }

    /**
     * @param Entity $entity
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


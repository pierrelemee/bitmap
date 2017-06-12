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

    protected function getDefaultAutoload()
    {
        return false;
    }

    protected function getDefaultAutosave()
    {
        return false;
    }

    public function joinClauses(Mapper $mapper, $name)
    {
        return [
            $this->joinClause($mapper->getTable(), $this->column, $this->via, $this->via, $this->viaSourceColumn),
            $this->joinClause($this->via, $this->viaTargetColumn, $this->getMapper()->getTable(), $name, $this->column)
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


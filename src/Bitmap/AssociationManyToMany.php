<?php

namespace Bitmap;



use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Query\Clauses\Join;

abstract class AssociationManyToMany extends Association
{
    /**
     * @var string $via the name of the table to join through
     */
    protected $via;
    protected $viaSourceColumn;
    protected $viaTargetColumn;
    protected $targetColumn;
    
    public function __construct($name, $class, $column, Via $via, $targetColumn = null)
    {
        parent::__construct($name, $class, $column);
        $this->via = $via;
        $this->targetColumn = $targetColumn;
    }

    protected function getDefaultAutoload()
    {
        return false;
    }

    protected function getDefaultAutosave()
    {
        return false;
    }

    public function joinClauses(Mapper $mapper, $alias)
    {
        return [
            Join::create()
                ->setFromTable($mapper->getTable())
                ->setFromColumn($this->column)
                ->setToTable($this->via->getTable())
                ->setToColumn($this->via->getSourceColumn()),
            Join::create()
                ->setFromTable($this->via->getTable())
                ->setFromColumn($this->via->getTargetColumn())
                ->setToTable($this->getMapper()->getTable())
                ->setToTable($alias)
                ->setToColumn($this->targetColumn ? : Bitmap::current()->getMapper($this->class)->getPrimary()->getColumn())
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


<?php

namespace Bitmap;



use Bitmap\Associations\ManyToMany\Via;

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

    public function joinClauses(Mapper $mapper, $name)
    {
        return [
            $this->joinClause($mapper->getTable(), $this->column, $this->via->getTable(), $this->via->getTable(), $this->via->getSourceColumn()),
            $this->joinClause($this->via->getTable(), $this->via->getTargetColumn(), $this->getMapper()->getTable(), $name, $this->targetColumn ? : Bitmap::current()->getMapper($this->class)->getPrimary()->getColumn())
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


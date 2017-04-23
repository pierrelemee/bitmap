<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;

abstract class AssociationManyToMany extends Association
{
    protected $through;
    protected $leftReference;
    protected $rightReference;
    
    public function __construct($name, $class, $right, $through, $leftReference, $rightReference)
    {
        parent::__construct($name, $class, $right);
        $this->through = $through;
        $this->leftReference = $leftReference;
        $this->rightReference = $rightReference;
    }

    public function joinClauses($name, $depth)
    {
        return [
            $this->joinClause($name, $this->name, $this->through, $this->leftReference),
            $this->joinClause($this->through, $this->rightReference, $this->getMapper()->getTable(), $this->right)
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

    public function set(ResultSet $result, Entity $entity, Context $context, $depth = 0)
    {
        $this->setEntities($entity, $this->getMapper()->loadAll($result));
    }

    /**
     * @param Entity $entity
     */
    protected abstract function setEntities(Entity $entity, array $entities);
}


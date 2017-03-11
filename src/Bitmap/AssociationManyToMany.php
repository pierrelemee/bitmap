<?php

namespace Bitmap;

abstract class AssociationManyToMany extends Association
{
    protected $through;
    protected $leftReference;
    protected $rightReference;
    
    public function __construct($name, Mapper $mapper, $right, $through, $leftReference, $rightReference)
    {
        parent::__construct($name, $mapper, $right);
        $this->through = $through;
        $this->leftReference = $leftReference;
        $this->rightReference = $rightReference;
    }

    public function joinClauses(Mapper $left)
    {
        return [
            $this->joinClause($left->getTable(), $this->name, $this->through, $this->leftReference),
            $this->joinClause($this->through, $this->rightReference, $this->mapper->getTable(), $this->right)
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


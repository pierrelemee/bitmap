<?php

namespace Bitmap\Associations;


use Bitmap\AssociationManyToMany;


use Bitmap\Entity;

use ReflectionMethod;

class MethodAssociationManyToMany extends AssociationManyToMany
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $column, $via, $viaSourceColumn, $viaTargetColumn)
    {
        parent::__construct($name, $class, $column, $via, $viaSourceColumn, $viaTargetColumn);
        $this->getter = $getter;
        $this->setter = $setter;
    }

    protected function getEntities(Entity $entity)
    {
        return $this->getter->invoke($entity);
    }

    protected function setEntities(Entity $entity, array $entities)
    {
        $this->setter->invoke($entity, $entities);
    }

    public static function fromMethods($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $through, $sourceReference, $targetReference, $column = null)
    {
        return new MethodAssociationManyToMany($name, $class, $getter, $setter, $through, $sourceReference, $targetReference, $column);
    }
}
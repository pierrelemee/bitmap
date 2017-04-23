<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\AssociationManyToMany;
use Bitmap\AssociationOneToMany;
use Bitmap\AssociationOne;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionMethod;

class MethodAssociationManyToMany extends AssociationManyToMany
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $through, $sourceReference, $targetReference, $target)
    {
        parent::__construct($name, $class, $target, $through, $sourceReference, $targetReference);
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
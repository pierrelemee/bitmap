<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\AssociationOneToMany;
use Bitmap\AssociationOne;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionMethod;

class MethodAssociationOneToMany extends AssociationOneToMany
{
    protected $getter;
    protected $setter;

    public function __construct($name, Mapper $mapper, ReflectionMethod $getter, ReflectionMethod $setter, $target)
    {
        parent::__construct($name, $mapper, $target);
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

    public static function fromMethods($name, Mapper $mapper, ReflectionMethod $getter, ReflectionMethod $setter, $column = null)
    {
        return new MethodAssociationOneToMany($name, $mapper, $getter, $setter, $column);
    }
}
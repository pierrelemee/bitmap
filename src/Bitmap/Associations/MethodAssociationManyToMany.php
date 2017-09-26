<?php

namespace Bitmap\Associations;


use Bitmap\AssociationManyToMany;


use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Entity;

use ReflectionMethod;

class MethodAssociationManyToMany extends AssociationManyToMany
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $column, Via $via, $targetColumn = null)
    {
        parent::__construct($name, $class, $column, $via, $targetColumn);
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

    public static function fromMethods($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, Via $via, $column = null)
    {
        return new MethodAssociationManyToMany($name, $class, $getter, $setter, $via, $column);
    }
}
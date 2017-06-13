<?php

namespace Bitmap\Associations;


use Bitmap\AssociationOneToMany;

use Bitmap\Entity;

use ReflectionMethod;

class MethodAssociationOneToMany extends AssociationOneToMany
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $column, $options = null)
    {
        parent::__construct($name, $class, $column, $options);
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
}
<?php

namespace Bitmap\Associations;


use Bitmap\AssociationOne;
use Bitmap\Entity;

use ReflectionMethod;


class MethodAssociationOne extends AssociationOne
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $column, $options = [])
    {
        parent::__construct($name, $class, $column, $options);
        $this->getter = $getter;
        $this->setter = $setter;
    }

    protected function getEntity(Entity $entity)
    {
        return $this->getter->invoke($entity);
    }

    protected function setEntity(Entity $entity, Entity $associated)
    {
        $this->setter->invoke($entity, $associated);
    }
}
<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\AssociationOne;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionMethod;
use ReflectionClass;

class MethodAssociationOne extends AssociationOne
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $target)
    {
        parent::__construct($name, $class, $target);
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

    public static function fromMethods($name, $class, ReflectionMethod $getter, ReflectionMethod $setter, $column = null)
    {
        return new MethodAssociationOne($name, $class, $getter, $setter, $column);
    }
}
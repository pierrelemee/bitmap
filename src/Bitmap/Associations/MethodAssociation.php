<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionMethod;

class MethodAssociation extends Association
{
    protected $getter;
    protected $setter;

    public function __construct($name, Mapper $mapper, ReflectionMethod $getter, ReflectionMethod $setter, $target)
    {
        parent::__construct($name, $mapper, $target);
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
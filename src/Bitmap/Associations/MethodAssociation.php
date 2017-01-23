<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionMethod;

class MethodAssociation extends Association
{
    protected $method;

    public function __construct($name, Mapper $mapper, ReflectionMethod $method, $target)
    {
        parent::__construct($name, $mapper, $target);
        $this->method = $method;
    }

    protected function setValue(Entity $entity, Entity $associated)
    {
        $this->method->invoke($entity, $associated);
    }

}
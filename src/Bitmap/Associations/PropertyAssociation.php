<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionProperty;

class PropertyAssociation extends Association
{
    protected $property;

    public function __construct($name, Mapper $mapper, ReflectionProperty $property)
    {
        parent::__construct($name, $mapper);
        $this->property = $property;
    }

    protected function setValue(Entity $entity, Entity $associated)
    {
        $this->property->setValue($associated);
    }

}
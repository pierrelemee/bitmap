<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\AssociationOne;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionProperty;

class PropertyAssociationOne extends AssociationOne
{
    protected $property;

    public function __construct($name, Mapper $mapper, ReflectionProperty $property, $target)
    {
        parent::__construct($name, $mapper, $target);
        $this->property = $property;
    }

    protected function getEntity(Entity $entity)
    {
        return $this->property->getValue($entity);
    }

    protected function setEntity(Entity $entity, Entity $associated)
    {
        $this->property->setValue($associated);
    }



}
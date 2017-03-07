<?php

namespace Bitmap\Associations;

use Bitmap\Association;
use Bitmap\AssociationMultiple;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionProperty;

class PropertyAssociationMultiple extends AssociationMultiple
{
    protected $property;

    public function __construct($name, Mapper $mapper, ReflectionProperty $property, $target)
    {
        parent::__construct($name, $mapper, $target);
        $this->property = $property;
    }

    protected function getEntities(Entity $entity)
    {
        return $this->property->getValue($entity);
    }

    protected function setEntities(Entity $entity, array $entities)
    {
        $this->property->setValue($entity, $entities);
    }

}
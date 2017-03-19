<?php

namespace Bitmap\Associations;

use Bitmap\AssociationManyToMany;
use Bitmap\Entity;
use Bitmap\Mapper;
use ReflectionProperty;

class PropertyAssociationManyToMany extends AssociationManyToMany
{
    protected $property;

    public function __construct($name, Mapper $mapper, ReflectionProperty $property, $through, $sourceReference, $targetReference, $right)
    {
        parent::__construct($name, $mapper, $right, $through, $sourceReference, $targetReference);
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
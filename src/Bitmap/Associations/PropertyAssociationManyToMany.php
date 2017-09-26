<?php

namespace Bitmap\Associations;

use Bitmap\AssociationManyToMany;
use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Entity;

use ReflectionProperty;

class PropertyAssociationManyToMany extends AssociationManyToMany
{
    protected $property;

    public function __construct($name, $class, ReflectionProperty $property, $column, Via $via, $targetColumn)
    {
        parent::__construct($name, $class, $column, $via, $targetColumn);
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
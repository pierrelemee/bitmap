<?php

namespace Bitmap\Associations;


use Bitmap\AssociationOneToMany;
use Bitmap\Entity;

use ReflectionProperty;

class PropertyAssociationOneToMany extends AssociationOneToMany
{
    protected $property;

    public function __construct($name, $class, ReflectionProperty $property, $column, $options = null)
    {
        parent::__construct($name, $class, $column, $options);
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
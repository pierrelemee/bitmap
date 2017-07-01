<?php

namespace Bitmap\Associations;


use Bitmap\AssociationOne;
use Bitmap\Entity;

use ReflectionProperty;


class PropertyAssociationOne extends AssociationOne
{
    protected $property;

    public function __construct($name, $class, ReflectionProperty $property, $column, $options = [])
    {
        parent::__construct($name, $class, $column);
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
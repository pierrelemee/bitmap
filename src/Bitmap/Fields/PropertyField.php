<?php

namespace Bitmap\Fields;

use Bitmap\Field;
use Bitmap\Entity;
use ReflectionClass;
use ReflectionProperty;

class PropertyField extends Field
{
    /**
     * @var ReflectionProperty
     */
    protected $property;

    public function __construct(ReflectionProperty $property, $column = null)
    {
        parent::__construct($property->getName(), $column);
        $this->property = $property;
    }

    public function getValue(Entity $entity)
    {
        return $this->property->getValue($entity);
    }

    public function setValue(Entity $entity, $value)
    {
        $this->property->setValue($entity, $value);
    }

    /**
     * @param ReflectionProperty $property
     *
     *
     * @return PropertyField
     */
    public static function from(ReflectionProperty $property, $column = null)
    {
        return new PropertyField($property, $column);
    }

    public static function fromClass($name, ReflectionClass $class, $column = null)
    {
        return new PropertyField($class->getProperty($name), $column);
    }
}
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

    public function __construct(ReflectionProperty $property, $name = null)
    {
        parent::__construct($name ? : $property->getName());
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
     * @return PropertyField
     */
    public static function from(ReflectionProperty $property, $name = null)
    {
        return new PropertyField($property, $name);
    }

    public static function fromClass($name, ReflectionClass $class)
    {
        return new PropertyField($class->getProperty($name));
    }
}
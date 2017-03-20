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

    public function __construct($name, ReflectionProperty $property)
    {
        parent::__construct($name);
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
     * @param string $name
     *
     * @return PropertyField
     */
    public static function from(ReflectionProperty $property, $name = null)
    {
        return new PropertyField($name ? : $property->getName(), $property);
    }

    /**
     * @param $name
     * @param ReflectionClass $class
     *
     * @return PropertyField
     */
    public static function fromClass($name, ReflectionClass $class, $property)
    {
        return new PropertyField($name, $class->getProperty($property ? : $name));
    }
}
<?php

namespace PierreLemee\Bitmap\Fields;

use PierreLemee\Bitmap\Field;
use PierreLemee\Bitmap\Entity;
use ReflectionProperty;

class PropertyField extends Field
{
    protected $attribute;

    public function __construct($name, $type, $class, $alias = null)
    {
        $name = $alias ? : $name;
        parent::__construct($name, $type, $class);
        $this->attribute = new ReflectionProperty($class, $name);
    }

    public function getValue(Entity $entity)
    {
        return $this->attribute->getValue($entity);
    }

    public function setValue(Entity $entity, $value)
    {
        $this->attribute->setValue($entity, $value);
    }

}
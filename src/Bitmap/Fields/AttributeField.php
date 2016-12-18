<?php

namespace PierreLemee\Bitmap\Fields;

use PierreLemee\Bitmap\Field;
use PierreLemee\Bitmap\Entity;
use ReflectionProperty;

class AttributeField extends Field
{
    protected $attribute;

    public function __construct($name, $class)
    {
        parent::__construct($name, $class);
        $this->attribute = new ReflectionProperty($class, $this->name);
    }

    public function get(Entity $entity)
    {
        return $this->attribute->getValue($entity);
    }

    public function set(Entity $entity, $value)
    {
        $this->attribute->setValue($entity, $value);
    }

}
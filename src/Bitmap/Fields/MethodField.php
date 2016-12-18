<?php

namespace PierreLemee\Bitmap\Fields;

use PierreLemee\Bitmap\Field;
use PierreLemee\Bitmap\Entity;
use ReflectionMethod;

class MethodField extends Field
{
    protected $getter;
    protected $setter;

    public function __construct($name, $type, $class, $setter = null)
    {
        $name = preg_match("/^get/", $name) ? lcfirst(substr($name, 3)) : $name;
        parent::__construct($name, $type, $class);
        $this->getter = new ReflectionMethod($class, 'get' . ucfirst($this->name));
        $this->setter = $setter ? : new ReflectionMethod($class, 'set' . ucfirst($this->name));
    }

    public function getValue(Entity $entity)
    {
        return $this->getter->invoke($entity);
    }

    public function setValue(Entity $entity, $value)
    {
        $this->setter->invoke($entity, $value);
    }
}
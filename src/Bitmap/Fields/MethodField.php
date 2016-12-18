<?php

namespace PierreLemee\Bitmap\Fields;

use PierreLemee\Bitmap\Field;
use PierreLemee\Bitmap\Entity;
use ReflectionMethod;

class MethodField extends Field
{
    protected $getter;
    protected $setter;

    public function __construct($name, $class, $setter = null)
    {
        $name = preg_match("/^get/", $name) ? lcfirst(substr($name, 3)) : $name;
        parent::__construct($name, $class);
        $this->getter = new ReflectionMethod($class, 'get' . ucfirst($this->name));
        $this->setter = new ReflectionMethod($class, 'set' . ucfirst($name));
    }

    public function get(Entity $entity)
    {
        return $this->getter->invoke($entity);
    }

    public function set(Entity $entity, $value)
    {
        $this->setter->invoke($entity, $value);
    }
}
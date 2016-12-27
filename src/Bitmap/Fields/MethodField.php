<?php

namespace PierreLemee\Bitmap\Fields;

use PierreLemee\Bitmap\Field;
use PierreLemee\Bitmap\Entity;
use ReflectionMethod;
use ReflectionClass;

class MethodField extends Field
{
    /**
     * @var ReflectionMethod
     */
    protected $getter;
    /**
     * @var ReflectionMethod
     */
    protected $setter;

    public function __construct($name, ReflectionMethod $getter, ReflectionMethod $setter)
    {
        parent::__construct(preg_match("/^get/", $name) ? lcfirst(substr($name, 3)) : $name);
        $this->getter = $getter;
        $this->setter = $setter;
    }

    public function getValue(Entity $entity)
    {
        return $this->getter->invoke($entity);
    }

    public function setValue(Entity $entity, $value)
    {
        $this->setter->invoke($entity, $value);
    }

    public static function fromClass($name, ReflectionClass $class, $setter = null)
    {
        return new MethodField($name, $class->getMethod("get" . ucfirst($name)), $class->getMethod($setter ? :"set" . ucfirst($name)));
    }

    public static function fromMethod($name, ReflectionMethod $getter)
    {
        return new MethodField($name, $getter, $getter->getDeclaringClass()->getMethod(preg_replace("/^get/", "set", $name)));
    }

    public static function fromMethods($name, ReflectionMethod $getter, ReflectionMethod $setter)
    {
        return new MethodField($name, $getter, $setter);
    }
}
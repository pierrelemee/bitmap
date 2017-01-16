<?php

namespace Bitmap\Fields;

use Bitmap\Field;
use Bitmap\Entity;
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

    public function __construct($name, ReflectionMethod $getter, ReflectionMethod $setter, $alias = null)
    {
        parent::__construct($alias ? : (preg_match("/^get/", $name) ? lcfirst(substr($name, 3)) : $name));
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
        return new MethodField($name, $class->getMethod(self::getterForName($name)),
            $class->getMethod($setter ? : self::setterForName($name)));
    }

    public static function fromMethod($name, ReflectionMethod $getter)
    {
        return self::fromMethods($name, $getter, self::setterForGetter($getter));
    }

    public static function fromMethods($name, ReflectionMethod $getter, ReflectionMethod $setter)
    {
        return new MethodField($name, $getter, $setter);
    }

    public static function getterForName($name)
    {
        return "get" . ucfirst($name);
    }

    public static function setterForName($name)
    {
        return "set" . ucfirst($name);
    }

    /**
     * @param ReflectionMethod $getter
     *
     * @return ReflectionMethod
     */
    public static function setterForGetter(ReflectionMethod $getter)
    {
        return $getter->getDeclaringClass()->getMethod(preg_replace("/^get/", "set", $getter));
    }
}
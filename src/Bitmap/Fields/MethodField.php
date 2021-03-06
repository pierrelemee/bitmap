<?php

namespace Bitmap\Fields;

use Bitmap\Bitmap;
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

    public function __construct($name, ReflectionMethod $getter, ReflectionMethod $setter, $type = Bitmap::TYPE_STRING, $column = null, $nullable = false)
    {
        parent::__construct($name, $type, $column, $nullable);
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

    public static function fromClass($name, ReflectionClass $class, $getter = null, $setter = null)
    {
        if (null === $getter) {
            if ($class->hasMethod($name)) {
                $getter = $class->getMethod($name);
                $setter = self::setterForGetter($getter);
            } else if ($class->hasMethod(self::getterForName($name))) {
                $getter = $class->getMethod(self::getterForName($name));
                $setter = self::setterForGetter($getter);
            } else {
                // TODO: throw a verbose exception
            }
        } else {
            if ($class->hasMethod($getter)) {
                $getter = $class->getMethod($getter);
                $setter = self::setterForGetter($getter);
            } else if ($class->hasMethod(self::getterForName($getter))) {
                $getter = $class->getMethod(self::getterForName($getter));
                $setter = self::setterForGetter($getter);
            }
        }

        if (null === $setter) {
            $setter = self::setterForGetter($getter);
        }

        return new MethodField($name, $getter, $setter);
    }

    /**
     * @param ReflectionMethod $getter
     * @param null $name
     *
     * @return MethodField
     */
    public static function fromMethod(ReflectionMethod $getter, $name = null)
    {
        return new MethodField(
            $name ? : $getter->getName(),
            $getter,
            self::setterForGetter($getter)
        );
    }

    /**
     * @param ReflectionMethod $getter
     * @param ReflectionMethod $setter
     * @param null $name
     *
     * @return MethodField
     */
    public static function fromMethods(ReflectionMethod $getter, ReflectionMethod $setter, $name = null)
    {
        if (null === $name) {
            $name = preg_match("/^get/", $getter->getName()) ? lcfirst(substr($getter->getName(), 3)) : $getter->getName();
        }
        return new MethodField($name, $getter, $setter);
    }

    public static function getterForName($name)
    {
        return "get" . ucfirst($name);
    }

    public static function nameForGetter($name)
    {
        return strpos(strtolower($name),"get") === 0 ? lcfirst(substr($name, 3)) : (strpos(strtolower($name),"is") === 0 ? lcfirst(substr($name, 2)) : $name);
    }

    public static function setterForName($name)
    {
        return "set" . ucfirst($name);
    }

    public static function nameForSetter($name)
    {
        return strpos(strtolower($name),"set") === 0 ? lcfirst(substr($name, 3)) : $name;
    }

    /**
     * @param ReflectionMethod $getter
     *
     * @return ReflectionMethod
     */
    public static function setterForGetter(ReflectionMethod $getter)
    {
        return $getter->getDeclaringClass()->getMethod(preg_replace("/^get/", "set", $getter->name));
    }
}
<?php

namespace Bitmap\Reflection;

use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

class Annotations
{
    protected $annotations;

    private function __construct(array $comments)
    {
        $this->annotations = [];
        foreach ($comments as $comment) {
            $elements = array_filter(explode(" ", $comment), function ($element) {
                return strlen(trim($element)) > 0;
            });

            if (sizeof($elements) > 0 && $elements[0]{0} === '@') {
                $this->annotations[strtolower(substr($elements[0], 1))] = array_slice($elements, 1);
            }
        }
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->annotations[$name]);
    }

    /**
     * @param $name
     * @param $index
     * @param $default
     *
     * @return mixed
     */
    public function get($name, $index = null, $default = null)
    {
        if (isset($this->annotations[$name])) {
            return is_int($index) ? $this->annotations[$name][$index] : $this->annotations[$name];
        }

        return $default;
    }

    /**
     * @param $class ReflectionClass
     *
     * @return Annotations
     */
    public static function fromClass(ReflectionClass $class)
    {
        return self::fromDocComment($class->getDocComment());
    }

    /**
     * @param $property ReflectionProperty
     *
     * @return Annotations
     */
    public static function fromProperty(ReflectionProperty $property)
    {
        return self::fromDocComment($property->getDocComment());
    }

    /**
     * @param $method ReflectionMethod
     *
     * @return Annotations
     */
    public static function fromMethod(ReflectionMethod $method)
    {
        return self::fromDocComment($method->getDocComment());
    }

    private static function fromDocComment($doc)
    {
        $comments = [];

        foreach (explode("\n", $doc) as $line) {
            $line = trim(preg_replace("/^\\*/", "", trim($line)));

            if (strlen($line) > 0) {
                $comments[] = $line;
            }
        }

        return new Annotations($comments);
    }
}
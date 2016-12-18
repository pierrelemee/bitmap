<?php

namespace PierreLemee\Bitmap\Mappers;

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Fields\MethodField;
use PierreLemee\Bitmap\Fields\PropertyField;
use PierreLemee\Bitmap\Mapper;
use ReflectionClass;

class AnnotationMapper extends Mapper
{
    protected $reflection;

    public function __construct($class, $table = null)
    {
        parent::__construct($class, $table);
        $this->reflection = new ReflectionClass($class);

        $annotations = self::annotations($this->reflection->getDocComment());

        if (isset($annotations['table'])) {
            $this->setTable($annotations['table'][0]);
        }

        foreach ($this->reflection->getProperties() as $property) {
            $annotations = self::annotations($property->getDocComment());

            if (isset($annotations['field'])) {
                if ($property->isPublic()) {
                    $this->addField(
                        new PropertyField(
                            $property->getName(),
                            isset($annotations['type']) ? $annotations['type'][0] : Bitmap::TYPE_STRING,
                            $class
                        )
                    );
                } else {
                    $this->addField(
                        new MethodField(
                            $property->getName(),
                            isset($annotations['type']) ? $annotations['type'][0] : Bitmap::TYPE_STRING,
                            $class,
                            isset($annotations['setter']) && sizeof($annotations['setter']) > 0 ? $annotations['setter'][0] : null,
                            sizeof($annotations['field']) > 0 ? $annotations['field'][0] : null
                        )
                    );
                }
            }
        }

        foreach ($this->reflection->getMethods() as $method) {
            $annotations = self::annotations($method->getDocComment());

            if (isset($annotations['field'])) {
                $this->addField(
                    new MethodField(
                        $method->getName(),
                        isset($annotations['type']) ? $annotations['type'][0] : Bitmap::TYPE_STRING,
                        $class,
                        isset($annotations['setter']) ? $annotations['setter'] : null
                    )
                );
            }
        }
    }

    private static function annotations($doc)
    {
        $annotations = [];

        foreach (self::comments($doc) as $comment) {
            $elements = array_filter(explode(" ", $comment), function ($element) {
                return strlen(trim($element)) > 0;
            });

            if (sizeof($elements) > 0 && $elements[0]{0} === '@') {
                $annotations[strtolower(substr($elements[0], 1))] = array_slice($elements, 1);
            }
        }

        return $annotations;
    }

    /**
     * @param $doc
     * @return string[]
     */
    private static function comments($doc)
    {
        $comments = [];

        foreach (explode("\n", $doc) as $line) {
            $line = trim(preg_replace("/^\\*/", "", trim($line)));
            if ("" !== $line) {
                $comments[] = $line;
            }
        }

        return $comments;
    }

    /**
     * @param $class
     * @return Mapper
     */
    public static function of($class)
    {
        return new AnnotationMapper(is_object($class) ? get_class($class) : $class);
    }
}
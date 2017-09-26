<?php

namespace Bitmap;

use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Exceptions\MapperException;
use Bitmap\Fields\MethodField;
use Bitmap\Reflection\Annotations;

class AnnotatedEntity extends ArrayMappedEntity
{
    protected function getMappingArray()
    {
        $mapping = [];
        $reflection = new \ReflectionClass(get_called_class());
        $annotations = Annotations::fromClass($reflection);
        if ($annotations->has('table')) {
            $mapping['table'] = $annotations->get('table', 0);
        }

        foreach ($reflection->getProperties() as $property) {
            $annotations = Annotations::fromProperty($property);
            if ($annotations->has('primary')) {
                $mapping['primary'] = self::fieldFromAnnotations($annotations, $property->getName(), true);
            } else if ($annotations->has('field')) {
                $field = self::fieldFromAnnotations($annotations, $property->getName());
                $mapping['fields'][$field['name']] = $field;
            } else if ($annotations->has('association')) {
                $association = self::associationFromAnnotations($annotations, $property->getName());
                $mapping['associations'][$association['name']] = $association;
            }
        }

        foreach ($reflection->getMethods() as $method) {
            $annotations = Annotations::fromMethod($method);
            if ($annotations->has('primary')) {
                $mapping['primary'] = self::fieldFromAnnotations($annotations, MethodField::nameForGetter($method->getName()), true);
            } else if ($annotations->has('field')) {
                $field = self::fieldFromAnnotations($annotations, MethodField::nameForGetter($method->getName()));
                $mapping['fields'][$field['name']] = $field;
            } else if ($annotations->has('association')) {
                $association = self::associationFromAnnotations($annotations, MethodField::nameForGetter($method->getName()));
                $mapping['associations'][$association['name']] = $association;
            }
        }

        return $mapping;
    }

    private static function fieldFromAnnotations(Annotations $annotations, $name, $primary = false) {
        $key = $primary ? 'primary' : 'field';
        $column = $name;

        switch (count($annotations->get($key))) {
            case 1:
                $column = $annotations->get($key, 0);
                break;
            case 2:
                $name = $annotations->get($key, 0);
                $column = $annotations->get($key, 1);
                break;
        }
        return [
            'name' => $name,
            'column' => $column,
            'type' => $annotations->get('type', 0),
            'getter' => $annotations->get('getter', 0),
            'setter' => $annotations->get('setter', 0)
        ];
    }

    private static function associationFromAnnotations(Annotations $annotations, $name) {
        $column = $name;
        if (count($annotations->get('association')) === 0) {
            throw new MapperException("Missing target class for association annotation $name");
        }

        $class = $annotations->get('association', 0);
        if (!$annotations->has('type')) {
            throw new MapperException("Missing required annotation @type for association annotation $name");
        }

        if (count($annotations->get('type')) === 0) {
            throw new MapperException("Missing association type for association annotation $name");
        }
        $type = $annotations->get('type', 0);

        switch ($type) {
            case 'one':
                switch (count($annotations->get('type'))) {
                    case 2:
                        $column = $annotations->get('type', 1);
                        break;
                    case 3:
                        $name = $annotations->get('type', 1);
                        $column = $annotations->get('type', 2);
                        break;
                }

                return [
                    'name' => $name,
                    'type' => $type,
                    'column' => $column,
                    'class' => $class,
                    'getter' => $annotations->get('getter', 0),
                    'setter' => $annotations->get('setter', 0)
                ];
            case 'one-to-many':
                switch (count($annotations->get('type'))) {
                    case 2:
                        $column = $annotations->get('type', 1);
                        break;
                    case 3:
                        $name = $annotations->get('type', 1);
                        $column = $annotations->get('type', 2);
                        break;
                }
                return [
                    'name' => $name,
                    'type' => $type,
                    'column' => $column,
                    'class' => $class,
                    'getter' => $annotations->get('getter', 0),
                    'setter' => $annotations->get('setter', 0)
                ];
            case 'many-to-many':
                $target = null;

                switch (count($annotations->get('type'))) {
                    case 3:
                        $column = $annotations->get('type', 1);
                        $target = $annotations->get('type', 2);
                        break;
                    case 4:
                        $name = $annotations->get('type', 1);
                        $column = $annotations->get('type', 2);
                        $target = $annotations->get('type', 3);
                        break;
                }

                $via = Via::fromAnnotation($annotations->get('via', 0, ''));

                if (null === $via) {
                    throw new MapperException("Invalid via directive annotation for association many-to-many $name");
                }

                return [
                    'name'   => $name,
                    'type'   => $type,
                    'via'    => $via,
                    'column' => $column,
                    'target' => $target,
                    'class'  => $class,
                    'getter' => $annotations->get('getter', 0),
                    'setter' => $annotations->get('setter', 0)
                ];
            default:
                throw new MapperException("Invalid association type '$type' for association annotation $name");
        }
    }
}
<?php

namespace Bitmap;

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
            } else {
                if ($annotations->has('field')) {
                    $field = self::fieldFromAnnotations($annotations, $property->getName());
                    $mapping['fields'][$field['name']] = $field;
                }
            }
        }

        foreach ($reflection->getMethods() as $method) {
            $annotations = Annotations::fromMethod($method);
            if ($annotations->has('primary')) {
                $mapping['primary'] = self::fieldFromAnnotations($annotations, MethodField::nameForGetter($method->getName()), true);
            } else {
                if ($annotations->has('field')) {
                    $field = self::fieldFromAnnotations($annotations, MethodField::nameForGetter($method->getName()));
                    $mapping['fields'][$field['name']] = $field;
                }
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
}
<?php

namespace Bitmap\Mappers;

use Bitmap\Associations\MethodAssociation;
use Bitmap\Associations\PropertyAssociation;
use Bitmap\Entity;
use Bitmap\Reflection\Annotations;
use Bitmap\Bitmap;
use Bitmap\Fields\MethodField;
use Bitmap\Fields\PropertyField;
use Bitmap\Mapper;
use ReflectionClass;

class AnnotationMapper extends Mapper
{
    protected $reflection;

    public function __construct($class, $table = null)
    {
        parent::__construct($class, $table);
        $this->reflection = new ReflectionClass($class);

        $annotations = Annotations::fromClass($this->reflection);

        if ($annotations->has('table')) {
            $this->setTable($annotations->get('table', 0, null));
        }

        foreach ($this->reflection->getProperties() as $property) {
            $annotations = Annotations::fromProperty($property);

            if ($annotations->has('field')) {
                $column = $annotations->get('field', 0, $property->getName());
                if ($property->isPublic()) {
                    $field = PropertyField::from($property, $column)
                        ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                        ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                        ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))));
                    if (in_array('primary', array_map('strtolower', $annotations->get('field')))) {
                        $this->primary = $field;
                    }
                    $this->addField($field);

                } else {
                    $field = MethodField::fromClass($property->getName(), $this->reflection, $annotations->get('setter', 0), $column)
                        ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                        ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                        ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))));
                    if (in_array('primary', array_map('strtolower', $annotations->get('field')))) {
                        $this->primary = $field;
                    }
                    $this->addField($field);
                }
            } else if ($annotations->has("association")) {
                $name = $annotations->get('association', 0, $property->getName());
                if ($property->isPublic()) {
                    $this->addAssociation(new PropertyAssociation($name, Entity::getMapper($annotations->get("association", 2, null)), $property, $annotations->get("association", 3, null)));
                } else {
                    $getter = $this->reflection->getMethod("get" . ucfirst($property->getName()));
                    $setter = $this->reflection->getMethod("set" . ucfirst($property->getName()));
                    $this->addAssociation(new MethodAssociation($name, Entity::getMapper($annotations->get("association", 2, null)), $getter, $setter, $annotations->get("association", 3, null)));
                }
            }
        }

        foreach ($this->reflection->getMethods() as $method) {
            $annotations = Annotations::fromMethod($method);

            if ($annotations->has('field')) {
                $column = $annotations->get('field', 0, null);
                if ($annotations->has('setter') && sizeof($annotations->get('setter')) > 0) {
                    $field = MethodField::fromMethods(
                            $method->getName(),
                            $method,
                            $this->reflection->getMethod($annotations->get('setter', 0)),
                            $column
                        )
                        ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                        ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                        ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))));
                    if (in_array('primary', array_map('strtolower', $annotations->get('field')))) {
                        $this->primary = $field;
                    }
                    $this->addField($field);
                } else {
                    $field = MethodField::fromMethod(
                            $method->getName(),
                            $method,
                            $column
                        )
                        ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                        ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                        ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))));
                    if (in_array('primary', array_map('strtolower', $annotations->get('field')))) {
                        $this->primary = $field;
                    }

                    $this->addField($field);
                }
            } else if ($annotations->has("association")) {
                $this->addAssociation(new MethodAssociation($annotations->get("association", 0, $method->getName()), Entity::getMapper($annotations->get("association", 2, null)), $method, MethodField::setterForGetter($method), $annotations->get("association", 3, null)));
            }
        }
    }

    /**
     * @param $class
     * @return Mapper
     */
    public static function from($class)
    {
        return new AnnotationMapper(is_object($class) ? get_class($class) : $class);
    }
}
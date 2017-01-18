<?php

namespace Bitmap\Mappers;

use Bitmap\Associations\MethodAssociation;
use Bitmap\Associations\PropertyAssociation;
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
                    $this->addField(
                        PropertyField::from($property, $column)
                            ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                            ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                            ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))))
                    );
                } else {
                    $this->addField(
                        MethodField::fromClass($property->getName(), $this->reflection, $annotations->get('setter', 0), $column)
                            ->setTransformer(Bitmap::getTransformer($annotations->get('type', 0, null)))
                            ->setIncremented(in_array('incremented', array_map('strtolower', $annotations->get('field'))))
                            ->setNullable(in_array('nullable', array_map('strtolower', $annotations->get('field'))))
                    );
                }
            } else if ($annotations->has("association")) {
                if ($property->isPublic()) {
                    $this->addAssociation(new PropertyAssociation($property->getName(), Mapper::of($annotations->get("association", 1, null)), $property));
                } else {
                    $method = $this->reflection->getMethod("set" . ucfirst($property->getName()));
                    $this->addAssociation(new MethodAssociation($property->getName(), Mapper::of($annotations->get("association", 1, null)), $method));
                }
            }
        }

        foreach ($this->reflection->getMethods() as $method) {
            $annotations = Annotations::fromMethod($method);

            if ($annotations->has('field')) {
                $column = $annotations->get('field', 0, null);
                if ($annotations->has('setter') && sizeof($annotations->get('setter')) > 0) {
                    $this->addField(
                        MethodField::fromMethods(
                            $method->getName(),
                            $method,
                            $this->reflection->getMethod($annotations->get('setter', 0)),
                            $column
                        )
                    );
                } else {
                    $this->addField(
                        MethodField::fromMethod(
                            $method->getName(),
                            $method,
                            $column
                        )
                    );
                }
            } else if ($annotations->has("association")) {
                $this->addAssociation(new MethodAssociation($method->getName(), Mapper::of($annotations->get("association", 1, null)), $method));
            }
        }
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
<?php

namespace Chinook\Valid\Inline;

use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use Bitmap\Bitmap;
use ReflectionClass;

class MediaType extends Entity
{
    protected $id;
    protected $name;

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addField(
                MethodField::fromMethods('MediaTypeId', $reflection->getMethod('getId'), $reflection->getMethod('setId'), 'MediaTypeId')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER)),
                true
            )
            ->addField(
                MethodField::fromClass('Name', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
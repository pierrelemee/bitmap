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

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'MediaTypeId')
            ->addField('name', Bitmap::TYPE_STRING, 'Name');
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
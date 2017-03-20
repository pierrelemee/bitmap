<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use ReflectionClass;

class Artist extends Entity
{
    protected $id;
    public $name;

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addPrimary(
                MethodField::fromClass('ArtistId', $reflection, 'getId')
                ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                PropertyField::fromClass('Name', $reflection, 'name')
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
}
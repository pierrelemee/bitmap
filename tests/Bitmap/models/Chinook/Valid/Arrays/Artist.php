<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use ReflectionClass;

class Artist extends ArrayMappedEntity
{
    protected $id;
    public $name;

    protected function getMapping()
    {
        return [
            'primary' => [
                'column' => 'ArtistId',
                'type' => 'int',
                'incremented' => true,
                'nullable' => false,
                'getter' => 'getId'

            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => 'string',
                    'property' => 'name'
                ]
            ]
        ];
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
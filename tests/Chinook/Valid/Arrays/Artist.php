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
    /**
     * @var Album[]
     */
    protected $albums;

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'ArtistId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type'   => Bitmap::TYPE_STRING
                ]
            ],
            'associations' => [
                'albums' => [
                    'type'   => 'one-to-many',
                    'column' => 'ArtistId',
                    'class'  => Album::class
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

    /**
     * @return Album[]
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    }
}
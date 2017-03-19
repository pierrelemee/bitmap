<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Entity;
use Chinook\Valid\Inline\MediaType;
use Chinook\Valid\Inline\Genre;
use ReflectionClass;
use Bitmap\Mapper;
use Bitmap\Bitmap;
use Bitmap\Fields\MethodField;

class Track extends ArrayMappedEntity
{
    protected $id;
    protected $name;
    protected $genre;
    protected $media;
    protected $composer;
    protected $milliseconds;
    protected $bytes;
    protected $unitPrice;

    protected function getMapping()
    {
        return [
            'primary' => [
                'column' => 'TrackId',
                'type' => 'int',
                'incremented' => true,
                'nullable' => false,
                'getter' => 'getId'

            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => 'string',
                    'getter' => 'getName'
                ],
                'composer' => [
                    'column' => 'Composer',
                    'type' => 'string',
                    'getter' => 'getComposer'
                ],
                'milliseconds' => [
                    'column' => 'Milliseconds',
                    'type' => 'string',
                    'getter' => 'getMilliseconds'
                ],
                'bytes' => [
                    'column' => 'Bytes',
                    'type' => 'integer',
                    'getter' => 'getBytes'
                ],
                'unitPrice' => [
                    'column' => 'UnitPrice',
                    'type' => 'float',
                    'getter' => 'getUnitPrice'
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

    /**
     * @return Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param \Chinook\Valid\Inline\Genre $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return MediaType
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return mixed
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param mixed $composer
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
    }

    /**
     * @return mixed
     */
    public function getMilliseconds()
    {
        return $this->milliseconds;
    }

    /**
     * @param mixed $milliseconds
     */
    public function setMilliseconds($milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    /**
     * @return mixed
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @param mixed $bytes
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }
}
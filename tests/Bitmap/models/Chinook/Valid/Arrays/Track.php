<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;

class Track extends ArrayMappedEntity
{
    protected $id;
    protected $name;
    protected $album;
    protected $genre;
    protected $media;
    protected $composer;
    protected $milliseconds;
    protected $bytes;
    protected $unitPrice;

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'TrackId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => Bitmap::TYPE_STRING
                ],
                'composer' => [
                    'column' => 'Composer',
                    'type' => Bitmap::TYPE_STRING
                ],
                'milliseconds' => [
                    'column' => 'Milliseconds',
                    'type' => Bitmap::TYPE_STRING
                ],
                'bytes' => [
                    'column' => 'Bytes',
                    'type' => Bitmap::TYPE_INTEGER
                ],
                'unitPrice' => [
                    'column' => 'UnitPrice',
                    'type' => Bitmap::TYPE_FLOAT
                ]
            ],
            'associations' => [
                'album' => [
                    'type'   => 'one',
                    'class'  => Album::class,
                    'column' => 'AlbumId'
                ],
                'genre' => [
                    'type'   => 'one',
                    'class'  => Genre::class,
                    'column' => 'AlbumId'
                ],
                'media' => [
                    'type'   => 'one',
                    'class'  => MediaType::class,
                    'column' => 'AlbumId'
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
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
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
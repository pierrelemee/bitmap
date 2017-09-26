<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

/**
 * Class Track
 * @package Chinook\Valid\Annotated
 */
class Track extends AnnotatedEntity
{
    /**
     * @primary TrackId
     * @type integer
     * @var int $id
     */
    protected $id;
    /**
     * @field Name
     * @type string
     * @var int $name
     */
    protected $name;
    /**
     * @association Chinook\Valid\Annotated\Album
     * @type one AlbumId
     * @var Album $album
     */
    protected $album;
    /**
     * @association Chinook\Valid\Annotated\Genre
     * @type one GenreId
     * @var Album $genre
     */
    protected $genre;
    /**
     * @association Chinook\Valid\Annotated\MediaType
     * @type one MediaTypeId
     * @var MediaType $media
     */
    protected $media;
    /**
     * @field Composer
     * @type string
     * @var string $composer
     */
    protected $composer;
    /**
     * @field Milliseconds
     * @type integer
     * @var integer $milliseconds
     */
    protected $milliseconds;
    /**
     * @field Bytes
     * @type integer
     * @var integer $bytes
     */
    protected $bytes;
    /**
     * @field UnitPrice
     * @type float
     * @var float $unitPrice
     */
    protected $unitPrice;

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
     * @param \Chinook\Valid\Annotated\Genre $genre
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
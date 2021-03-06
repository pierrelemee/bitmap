<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Entity;
use Chinook\Valid\Inline\MediaType;
use Chinook\Valid\Inline\Genre;
use ReflectionClass;
use Bitmap\Mapper;
use Bitmap\Bitmap;
use Bitmap\Fields\MethodField;

class Track extends Entity
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

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'TrackId')
            ->addField('name', Bitmap::TYPE_STRING, 'Name')
            ->addField('composer', Bitmap::TYPE_STRING, 'Composer')
            ->addField('milliseconds', Bitmap::TYPE_INTEGER, 'Milliseconds')
            ->addField('bytes', Bitmap::TYPE_INTEGER, 'Bytes')
            ->addField('unitPrice', Bitmap::TYPE_FLOAT, 'UnitPrice')
	        ->addAssociationOne('album', Album::class, 'AlbumId')
            ->addAssociationOne('genre', Genre::class, 'GenreId')
            ->addAssociationOne('media', MediaType::class, 'MediaTypeId', null, null, []);
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
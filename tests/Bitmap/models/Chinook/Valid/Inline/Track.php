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

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addPrimary(
                MethodField::fromClass('TrackId', $reflection, 'id')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('Name', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addField(
                MethodField::fromClass('Composer', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addField(
                MethodField::fromClass('Milliseconds', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('Bytes', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('UnitPrice', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_FLOAT))
            )
	        ->addAssociationOne('album', Album::class, 'AlbumId')
            ->addAssociationOne('genre', Genre::class, 'GenreId')
            ->addAssociationOne('media', MediaType::class, 'MediaTypeId');
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
<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Entity;
use Chinook\MediaType;
use Chinook\Genre;
use ReflectionClass;
use Bitmap\Mapper;
use Bitmap\Bitmap;
use Bitmap\Fields\MethodField;

class Track extends Entity
{
    protected $id;
    protected $name;
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
            ->addField(
                MethodField::fromClass('id', $reflection, null, 'TrackId')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER)),
                true
            )
            ->addField(
                MethodField::fromClass('name', $reflection, null, 'Name')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addField(
                MethodField::fromClass('composer', $reflection, null, 'Composer')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addField(
                MethodField::fromClass('milliseconds', $reflection, null, 'Milliseconds')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('bytes', $reflection, null, 'Bytes')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('unitPrice', $reflection, null, 'UnitPrice')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_FLOAT))
            )
            ->addAssociation(
                MethodAssociationOne::fromMethods('GenreId', self::mapper('Chinook\Valid\Inline\Genre'), $reflection->getMethod('getGenre'), $reflection->getMethod('setGenre'), 'GenreId')
            )
            ->addAssociation(
                MethodAssociationOne::fromMethods('MediaTypeId', self::mapper('Chinook\Valid\Inline\MediaType'), $reflection->getMethod('getMedia'), $reflection->getMethod('setMedia'), 'MediaTypeId')
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

    /**
     * @return Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param Genre $genre
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
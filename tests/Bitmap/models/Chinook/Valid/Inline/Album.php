<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\MethodAssociationMultiple;
use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Entity;
use ReflectionClass;
use Bitmap\Bitmap;
use Bitmap\Mapper;
use Bitmap\Fields\MethodField;

class Album extends Entity
{
    protected $id;
    protected $title;
    /**
     * @var Artist
     */
    protected $artist;
    /**
     * @var Track[]
     */
    protected $tracks;

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addField(
                MethodField::fromClass('id', $reflection, null, 'AlbumId')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER)),
                true
            )
            ->addField(
                MethodField::fromClass('Title', $reflection)
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addAssociation(
                MethodAssociationOne::fromMethods('ArtistId', self::mapper('Chinook\Valid\Inline\Artist'), $reflection->getMethod('getArtist'), $reflection->getMethod('setArtist'), 'ArtistId')
            )
            ->addAssociation(
                MethodAssociationMultiple::fromMethods('AlbumId', self::mapper('Chinook\Valid\Inline\Track'), $reflection->getMethod('getTracks'), $reflection->getMethod('setTracks'), 'AlbumId')
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param mixed $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return Track[]
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @param Track[] $tracks
     */
    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
    }
}
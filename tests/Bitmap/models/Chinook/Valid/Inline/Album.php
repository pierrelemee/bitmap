<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\MethodAssociationOneToMany;
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
            ->addPrimary(
                MethodField::fromClass('AlbumId', $reflection, 'getId')
                    ->setType(Bitmap::TYPE_INTEGER)
            )
            ->addField(
                MethodField::fromClass('Title', $reflection)
                    ->setType(Bitmap::TYPE_STRING)
            )
            ->addAssociationOne('artist', Artist::class, 'ArtistId')
            ->addAssociation(
                MethodAssociationOneToMany::fromMethods('AlbumId', Track::class, $reflection->getMethod('getTracks'), $reflection->getMethod('setTracks'), 'AlbumId')
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
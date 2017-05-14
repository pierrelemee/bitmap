<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class Artist extends Entity
{
    protected $id;
    public $name;
    /**
     * @var Album[]
     */
    protected $albums;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'ArtistId')
            ->addField('name', Bitmap::TYPE_STRING, 'Name')
            ->addAssociationOneToMany('albums', Album::class, 'ArtistId');
    }

    public function onPostLoad()
    {

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
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

    public function getMapper()
    {
        return Mapper::from(get_class($this))
            ->addNewPrimary('id', Bitmap::TYPE_INTEGER, 'ArtistId')
            ->addNewField('name', Bitmap::TYPE_STRING, 'Name')
            ->addAssociationOneToMany('albums', Album::class, 'ArtistId');
    }

    public function onPostLoad()
    {
        /*
        if (null !== $this->albums) {
            foreach ($this->albums as $album) {
                if (null === $album->getArtist()) {
                    $album->setArtist($this);
                }
            }
        }
        */
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
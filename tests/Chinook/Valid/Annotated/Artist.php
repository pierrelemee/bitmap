<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

/**
 * Class Artist
 * @package Chinook\Valid\Annotated
 */
class Artist extends AnnotatedEntity
{
    /**
     * @primary ArtistId
     * @type integer
     * @var int
     */
    protected $id;
    /**
     * @field Name
     * @type string
     * @var string
     */
    public $name;
    /**
     * @association Chinook\Valid\Annotated\Album
     * @type one-to-many albums ArtistId
     * @var Album[]
     */
    protected $albums;

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
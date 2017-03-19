<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;
use Bitmap\Entity;

/**
 * Class Album
 * @package Chinook\Valid\Annotated
 */
class Album extends AnnotatedEntity
{
    /**
     * @field AlbumId primary
     * @type integer
     * @var int
     */
    protected $AlbumId;
    /**
     * @field Title
     * @type string
     * @var string
     */
    protected $Title;
    /**
     * @field ArtistId
     * @type integer
     * @setter setArtist
     * @var int
     */
    protected $ArtistId;

    /**
     * @return mixed
     */
    public function getAlbumId()
    {
        return $this->AlbumId;
    }

    /**
     * @param mixed $AlbumId
     */
    public function setAlbumId($AlbumId)
    {
        $this->AlbumId = $AlbumId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * @param mixed $Title
     */
    public function setTitle($Title)
    {
        $this->Title = $Title;
    }

    /**
     * @return mixed
     */
    public function getArtistId()
    {
        return $this->ArtistId;
    }

    /**
     * @param mixed $ArtistId
     */
    public function setArtist($ArtistId)
    {
        $this->ArtistId = $ArtistId;
    }
}
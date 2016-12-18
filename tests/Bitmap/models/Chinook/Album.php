<?php

namespace Chinook;

use PierreLemee\Bitmap\Entity;
/**
 * Class Album
 * @package Bitmap\models\Chinook
 */
class Album extends Entity
{
    /**
     * @field AlbumId
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
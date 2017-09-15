<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

/**
 * Class Album
 * @package Chinook\Valid\Annotated
 */
class Album extends AnnotatedEntity
{
    /**
     * @primary AlbumId
     * @type integer
     * @var int
     */
    protected $id;
    /**
     * @field Title
     * @type string
     * @var string
     */
    protected $title;
    /**
     * @association Chinook\Valid\Annotated\Artist
     * @type one ArtistId
     * @var Artist $artist
     */
    protected $artist;
    /**
     * @association Chinook\Valid\Annotated\Track
     * @type one-to-many AlbumId
     * @var Track[] $tracks
     */
    protected $tracks;

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
     * @param Artist $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return Track[]
     */
    public function getTracks(): array
    {
        return $this->tracks;
    }

    /**
     * @param Track[] $tracks
     */
    public function setTracks(array $tracks)
    {
        $this->tracks = $tracks;
    }
}
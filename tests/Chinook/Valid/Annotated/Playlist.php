<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

class Playlist extends AnnotatedEntity
{

    /**
     * @primary PlaylistId
     * @type integer
     * @var integer $id
     */
    protected $id;
    /**
     * @field Name
     * @type string
     * @var string $name
     */
    protected $name;
    /**
     * @association Chinook\Valid\Annotated\Track
     * @type many-to-many tracks PlaylistId TrackId
     * @via PlaylistTrack(PlaylistId,TrackId)
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
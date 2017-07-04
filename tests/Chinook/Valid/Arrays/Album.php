<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;

class Album extends ArrayMappedEntity
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

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'AlbumId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'title' => [
                    'column' => 'Title',
                    'type'   => Bitmap::TYPE_STRING
                ]
            ],
            'associations' => [
                'artist' => [
                    'type'   => 'one',
                    'class'  => Artist::class,
                    'column' => 'ArtistId'
                ],
                'tracks' => [
                    'type'   => 'one-to-many',
                    'class'  => Track::class,
                    'column' => 'AlbumId'
                ]

            ]
        ];
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
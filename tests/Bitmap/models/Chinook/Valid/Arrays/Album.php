<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;

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

    protected function getMapping()
    {
        return [
            'primary' => [
                'column' => 'AlbumId',
                'type' => 'int',
                'incremented' => true,
                'nullable' => false,
                'getter' => 'getId'

            ],
            'fields' => [
                'title' => [
                    'column' => 'Title',
                    'type' => 'string',
                    // Method
                    'getter' => 'getTitle'
                ]
            ],
            'associations' => [
                'ArtistId' => [
                    'class' => 'Chinook\Valid\Arrays\Artist',
                    'type' => 'one',
                    'options' => [
                        'target' => 'ArtistId',
                    ],
                    'getter' => 'getArtist',
                ],
                'AlbumId' => [
                    'class' => 'Chinook\Valid\Arrays\Track',
                    'type' => 'multiple',
                    'options' => [
                        'target' => 'AlbumId',
                    ],
                    'getter' => 'getTracks',
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
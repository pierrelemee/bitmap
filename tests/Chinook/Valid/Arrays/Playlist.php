<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Mapper;
use Bitmap\Bitmap;

class Playlist extends ArrayMappedEntity
{

    protected $id;
    protected $name;
    /**
     * @var Track[]
     */
    protected $tracks;

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'PlaylistId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => Bitmap::TYPE_STRING
                ]
            ],
            'associations' => [
                'tracks' => [
                    'type'   => 'many-to-many',
                    'class'  => Track::class,
                    'via' => [
                        'table'  => 'PlaylistTrack',
                        'source' => 'PlaylistId',
                        'target' => 'TrackId'
                    ],
                    'column' => 'PlaylistId',
                    'target' => 'TrackId'
                ],
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
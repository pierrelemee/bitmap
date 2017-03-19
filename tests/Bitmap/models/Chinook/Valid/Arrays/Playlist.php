<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;

class Playlist extends ArrayMappedEntity
{

    protected $id;
    protected $name;
    /**
     * @var Track[]
     */
    protected $tracks;

    protected function getMapping()
    {
        return [
            'primary' => [
                'column' => 'PlaylistId',
                'type' => 'int',
                'getter' => 'getId'

            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => 'string',
                    'getter' => 'getName'
                ]
            ],
            'associations' => [
                'PlaylistId' => [
                    'class' => 'Chinook\Valid\Arrays\Track',
                    'type' => 'many-to-many',
                    'options' => [
                        'through' => 'PlaylistTrack',
                        'sourceReference' => 'PlaylistId',
                        'targetReference' => 'trackId',
                        'right' => 'TrackId'
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
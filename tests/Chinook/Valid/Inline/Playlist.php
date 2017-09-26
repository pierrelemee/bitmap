<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Associations\MethodAssociationManyToMany;
use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use Bitmap\Bitmap;
use ReflectionClass;

class Playlist extends Entity
{

    protected $id;
    protected $name;
    /**
     * @var Track[]
     */
    protected $tracks;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'PlaylistId')
            ->addField('name', Bitmap::TYPE_STRING, 'Name')
            ->addAssociationManyToMany('tracks', Track::class,
                Via::fromTable('PlaylistTrack')
                    ->setSourceColumn('PlaylistId')
                    ->setTargetColumn('TrackId'), 'PlaylistId', 'TrackId');
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
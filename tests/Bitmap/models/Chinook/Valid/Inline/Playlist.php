<?php

namespace Chinook\Valid\Inline;

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

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addPrimary(
                MethodField::fromMethods('id', $reflection->getMethod('getId'), $reflection->getMethod('setId'), 'PlaylistId')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
            )
            ->addField(
                MethodField::fromClass('name', $reflection, null, 'Name')
                    ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            )
            ->addAssociation(
                MethodAssociationManyToMany::fromMethods(
                    'PlaylistId',
                    Track::class,
                    $reflection->getMethod('getTracks'),
                    $reflection->getMethod('setTracks'),
                    'PlaylistTrack',
                    'PlaylistId',
                    'TrackId',
                    'TrackId'
                )
            );
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
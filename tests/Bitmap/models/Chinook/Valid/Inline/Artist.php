<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use ReflectionClass;

class Artist extends Entity
{
    protected $ArtistId;
    public $Name;

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addField(
                MethodField::fromClass('ArtistId', $reflection)
                ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER)),
                true
            )
            ->addField(
                PropertyField::fromClass('Name', $reflection)
                ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_STRING))
            );
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
    public function setArtistId($ArtistId)
    {
        $this->ArtistId = $ArtistId;
    }
}
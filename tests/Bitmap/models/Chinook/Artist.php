<?php

namespace Chinook;

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Entity;
use PierreLemee\Bitmap\Fields\PropertyField;
use PierreLemee\Bitmap\Fields\MethodField;
use PierreLemee\Bitmap\Mapper;
use ReflectionClass;

class Artist extends Entity
{
    protected $ArtistId;
    public $Name;

    protected function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(get_class($this))
            ->addField(
                MethodField::fromClass('ArtistId', $reflection)
                ->setTransformer(Bitmap::getTransformer(Bitmap::TYPE_INTEGER))
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
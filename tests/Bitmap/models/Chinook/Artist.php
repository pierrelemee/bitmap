<?php

namespace Chinook;

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Entity;
use PierreLemee\Bitmap\Fields\AttributeField;
use PierreLemee\Bitmap\Fields\MethodField;
use PierreLemee\Bitmap\Mapper;

class Artist extends Entity
{
    protected $ArtistId;
    public $Name;

    protected function getMapper()
    {
        return Mapper::of(get_class($this))
            ->addField(new MethodField('ArtistId', Bitmap::TYPE_INTEGER, __CLASS__))
            ->addField(new AttributeField('Name', Bitmap::TYPE_STRING, __CLASS__));
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
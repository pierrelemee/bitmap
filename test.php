<?php

include __DIR__ . '/vendor/autoload.php';

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Entity;
use PierreLemee\Bitmap\Mapper;
use PierreLemee\Bitmap\Fields\MethodField;
use PierreLemee\Bitmap\Fields\AttributeField;

$dsn = 'sqlite://' . __DIR__ . '/Chinook_Sqlite_AutoIncrementPKs.sqlite';

Bitmap::addConnection('chinook', $dsn);

class Artist extends Entity
{
    protected $ArtistId;
    public $Name;

    protected function getMapper()
    {
        return Mapper::of(get_class($this))
            ->addField(new MethodField('ArtistId', __CLASS__))
            ->addField(new AttributeField('Name', __CLASS__));
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


$sql = 'select * from `Artist` where name like "The%"';

var_dump(Artist::select($sql));
<?php

include __DIR__ . '/vendor/autoload.php';

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Entity;

$dsn = 'sqlite://' . __DIR__ . '/Chinook_Sqlite_AutoIncrementPKs.sqlite';

Bitmap::addConnection('chinook', $dsn);

class Artist extends Entity
{
    protected $ArtistId;
    public $Name;

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
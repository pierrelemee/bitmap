<?php

namespace Chinook;

use Bitmap\Entity;

class Track extends Entity
{
    /**
     * @field TrackId incremented
     * @type integer
     * @var int
     */
    protected $id;
    /**
     * @field Name
     * @type string
     * @var string
     */
    protected $name;
    /**
     * @association Bitmap\models\Chinook\Genre id
     * @var Genre
     */
    protected $genre;
    /**
     * @field Composer
     * @type string
     * @var string
     */
    protected $composer;
    /**
     * @field Milliseconds nullable
     * @type integer
     * @var int
     */
    protected $milliseconds;
    /**
     * @field Bytes nullable
     * @type integer
     * @var int
     */
    protected $bytes;
    /**
     * @field UnitPrice
     * @type float
     * @var float
     */
    protected $unitPrice;

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
     * @return Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param Genre $genre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @param mixed $composer
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
    }

    /**
     * @return mixed
     */
    public function getMilliseconds()
    {
        return $this->milliseconds;
    }

    /**
     * @param mixed $milliseconds
     */
    public function setMilliseconds($milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    /**
     * @return mixed
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @param mixed $bytes
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }
}
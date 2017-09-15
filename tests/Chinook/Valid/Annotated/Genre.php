<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

/**
 * Class Genre
 * @package Chinook\Valid\Annotated
 */
class Genre extends AnnotatedEntity
{
    /**
     * @primary GenreId
     * @type integer
     * @var int $id
     */
    protected $id;
    /**
     * @field Name
     * @type string
     * @var string $name
     */
    protected $name;

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
}
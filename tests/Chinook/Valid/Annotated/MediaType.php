<?php

namespace Chinook\Valid\Annotated;

use Bitmap\Entity;

/**
 * Class MediaType
 * @package Chinook\Valid\Annotated
 */
class MediaType extends Entity
{
    /**
     * @field MediaTypeId incremented primary
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
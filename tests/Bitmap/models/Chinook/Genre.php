<?php

namespace Chinook;

use Bitmap\Entity;

class Genre extends Entity
{
    /**
     * @field GenreId incremented
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
}
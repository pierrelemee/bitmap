<?php

namespace Misc;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class Transport extends Entity
{
    public $id;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER);
    }

}
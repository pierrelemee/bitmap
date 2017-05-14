<?php

namespace Bitmap;

use Bitmap\Mappers\AnnotationMapper;

class AnnotatedEntity extends Entity
{
    public function createMapper()
    {
        return AnnotationMapper::from($this);
    }

}
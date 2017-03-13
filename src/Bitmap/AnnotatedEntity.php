<?php

namespace Bitmap;

use Bitmap\Mappers\AnnotationMapper;

class AnnotatedEntity extends Entity
{
    public function getMapper()
    {
        return AnnotationMapper::of($this);
    }

}
<?php

namespace Bitmap\Transformers;

use Bitmap\Transformer;
use Bitmap\Bitmap;

class IntegerTransformer extends Transformer
{
    public function getName()
    {
        return Bitmap::TYPE_INTEGER;
    }

    public function toObject($value)
    {
        return intval($value);
    }

    public function fromObject($value)
    {
        return $value;
    }
}
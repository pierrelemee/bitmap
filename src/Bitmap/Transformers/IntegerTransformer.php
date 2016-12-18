<?php

namespace PierreLemee\Bitmap\Transformers;

use PierreLemee\Bitmap\Transformer;
use PierreLemee\Bitmap\Bitmap;

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
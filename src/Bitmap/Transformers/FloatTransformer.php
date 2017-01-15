<?php

namespace PierreLemee\Bitmap\Transformers;

use PierreLemee\Bitmap\Transformer;
use PierreLemee\Bitmap\Bitmap;

class FloatTransformer extends Transformer
{
    public function getName()
    {
        return Bitmap::TYPE_FLOAT;
    }

    public function toObject($value)
    {
        return floatval($value);
    }

    public function fromObject($value)
    {
        return $value;
    }
}
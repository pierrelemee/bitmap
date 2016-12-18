<?php

namespace PierreLemee\Bitmap\Transformers;

use PierreLemee\Bitmap\Transformer;
use PierreLemee\Bitmap\Bitmap;

class StringTransformer extends Transformer
{
    public function getName()
    {
        return Bitmap::TYPE_STRING;
    }
    public function toObject($value)
    {
        return $value;
    }

    public function fromObject($value)
    {
        return sprintf('"%s"', $value);
    }
}
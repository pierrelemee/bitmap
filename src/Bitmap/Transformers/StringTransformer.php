<?php

namespace Bitmap\Transformers;

use Bitmap\Transformer;
use Bitmap\Bitmap;

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
        return sprintf('"%s"', addslashes($value));
    }
}
<?php

namespace Bitmap\Transformers;

use Bitmap\Transformer;
use Bitmap\Bitmap;
use DateTime;

class DateTransformer extends Transformer
{
    const DATE_FORMAT = 'Y-m-d';

    public function getName()
    {
        return Bitmap::TYPE_DATE;
    }

    public function toObject($value)
    {
        return DateTime::createFromFormat(self::DATE_FORMAT, $value);
    }

    public function fromObject($value)
    {
        return $value->format(self::DATE_FORMAT);
    }
}
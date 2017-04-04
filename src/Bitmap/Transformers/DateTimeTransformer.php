<?php

namespace Bitmap\Transformers;

use Bitmap\Transformer;
use Bitmap\Bitmap;
use DateTime;

class DateTimeTransformer extends Transformer
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    public function getName()
    {
        return Bitmap::TYPE_DATETIME;
    }

    public function toObject($value)
    {
        return DateTime::createFromFormat(self::DATE_FORMAT, $value);
    }

    public function fromObject($value)
    {
        return $value ? $value->format(self::DATE_FORMAT) : null;
    }
}
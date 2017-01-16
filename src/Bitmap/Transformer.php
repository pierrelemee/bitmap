<?php

namespace Bitmap;

abstract class Transformer
{
    public abstract function getName();

    public abstract function toObject($value);

    public abstract function fromObject($value);
}
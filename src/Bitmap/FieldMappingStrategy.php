<?php

namespace Bitmap;

/**
 * Class FieldMappingStrategy
 *
 * Responsible of mapping a result set array
 * @package Bitmap
 *
 */
abstract class FieldMappingStrategy
{
    public abstract function getPdoFetchingType();

    /**
     * @param Mapper $mapper
     * @param string $field
     * @param integer $depth
     *
     * @return string
     */
    public abstract function getFieldLabel(Mapper $mapper, $field, $depth = 0);
}
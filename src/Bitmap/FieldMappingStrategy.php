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
     * @param Field $field
     *
     * @return string
     */
    public abstract function getFieldLabel(Mapper $mapper, Field $field);
}
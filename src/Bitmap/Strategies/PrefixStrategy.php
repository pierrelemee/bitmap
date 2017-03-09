<?php

namespace Bitmap\Strategies;

use Bitmap\Field;
use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use PDO;

class PrefixStrategy extends FieldMappingStrategy
{
    public function getPdoFetchingType()
    {
        return PDO::FETCH_ASSOC;
    }

    public function getFieldLabel(Mapper $mapper, Field $field)
    {
        return "{$mapper->getTable()}.{$field->getColumn()}";
    }
}
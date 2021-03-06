<?php

namespace Bitmap\Strategies;


use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use PDO;

class PrefixStrategy extends FieldMappingStrategy
{
    public function getPdoFetchingType()
    {
        return PDO::FETCH_ASSOC;
    }

    public function getFieldLabel(Mapper $mapper, $field, $depth = 0)
    {
        $suffix = $depth > 0 ? $depth + 1 : '';
        return "{$mapper->getTable()}{$suffix}.{$field}";
    }
}
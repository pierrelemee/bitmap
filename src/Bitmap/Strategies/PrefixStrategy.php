<?php

namespace Bitmap\Strategies;

use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use PDO;

class PrefixStrategy extends FieldMappingStrategy
{
    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
    }

    public function getPdoFetchingType()
    {
        return PDO::FETCH_ASSOC;
    }

    protected function mapValues(array $result, array $mapping)
    {
        $values = [];

        foreach ($result as $key => $value) {
            if (false !== ($index = strpos($key, "."))) {
                $table = substr($key, 0, $index);
                if (isset($mapping[$table])) {
                    if (!isset($values[$table])) {
                        $values[$table] = [];
                    }
                    $values[$table][substr($key, $index + 1)] = $value;
                }
            }
        }

        return $values;
    }

    public function mapping()
    {
        $mapping = [$this->mapper->getTable() => []];

        foreach ($this->mapper->associations() as $association) {
            $mapping[$association->getMapper()->getTable()] = $association->getMapper()->fieldNames();
        }

        return $mapping;
    }
}
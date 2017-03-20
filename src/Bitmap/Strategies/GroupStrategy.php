<?php

namespace Bitmap\Strategies;

use Bitmap\Field;
use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use PDO;

class GroupStrategy extends FieldMappingStrategy
{
    protected $mapping;

    public function __construct()
    {
        $this->mapping = [];
    }

    public function getPdoFetchingType()
    {
        return PDO::FETCH_NUM;
    }

    public function setMapping(array $mapping)
    {
        $this->mapping = [];

        foreach ($mapping as $name => $rank) {
            $this->mapping[$name] = [];
            if (is_array($rank)) {
                foreach ($rank as $key => $value) {
                    if(is_int($key)) {
                        $this->mapping[$name][$value] = $key;
                    } else {
                        $this->mapping[$name][$key] = intval($value);
                    }
                }

            } else {
                $index = strpos($name, '.');
                if (false !== $index) {
                    $this->mapping[$name][substr($name, 0, $index)] = [substr($name, $index + 1) => $rank];
                }
            }
        }
    }

    public function getFieldLabel(Mapper $mapper, Field $field)
    {
        return isset($this->mapping[$mapper->getTable()][$field->getName()]) ? $this->mapping[$mapper->getTable()][$field->getName()] : null;
    }

}
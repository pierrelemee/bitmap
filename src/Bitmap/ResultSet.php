<?php

namespace Bitmap;

use PDOStatement;
use PDO;

class ResultSet
{
    /**
     * @var array
     */
    protected $values;

    public function __construct(PDOStatement $statement, Mapper $mapper, FieldMappingStrategy $strategy, $with = [])
    {
        $this->values = [];

        // Should ask to a Strategy for the fetch mode
        while (false !== $data = $statement->fetch($strategy->getPdoFetchingType())) {
            $this->read($mapper, $data, $strategy, $with);
        }
    }

    protected function value(Mapper $mapper, Field $field, array $data, FieldMappingStrategy $strategy, $index = 0)
    {
        if (($key = $strategy->getFieldLabel($mapper, $field, $index)) !== null && isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    protected function read(Mapper $mapper, array $data, FieldMappingStrategy $strategy, $with = [], $depth = 0)
    {
        $primary = $this->value($mapper, $mapper->getPrimary(), $data, $strategy, $depth);

        if (null !== $primary && !isset($this->values[$mapper->getClass()][$mapper->getPrimary()->getName()])) {
            $this->values[$mapper->getClass()][$depth][$primary] = [];
            foreach ($mapper->getFields() as $name => $field) {
                $this->values[$mapper->getClass()][$depth][$primary][$field->getName()] = $this->value($mapper, $field, $data, $strategy, $depth);
            }
        }

        foreach ($mapper->associations() as $name => $association) {
            if (isset($with[$association->getName()])) {
                $this->read($association->getMapper(), $data, $strategy, is_array($with[$association->getName()]) ? $with[$association->getName()] : [], $depth + 1);
            }
        }
    }

    public function getValuesOneEntity(Mapper $mapper, $depth = 0)
    {
        if (isset($this->values[$mapper->getClass()][$depth]) && sizeof($this->values[$mapper->getClass()][$depth]) > 0) {
            return array_values($this->values[$mapper->getClass()][$depth])[0];
        }
        return [];
    }

    public function getValuesAllEntity(Mapper $mapper, $depth = 0)
    {
        if (isset($this->values[$mapper->getClass()][$depth])) {
            return array_values($this->values[$mapper->getClass()][$depth]);
        }
        return [];
    }

}
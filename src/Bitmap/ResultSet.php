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

    public function __construct(PDOStatement $statement, Mapper $mapper, FieldMappingStrategy $strategy)
    {
        $this->values = [];

        // Should ask to a Strategy for the fetch mode
        while (false !== $data = $statement->fetch($strategy->getPdoFetchingType())) {
            $this->read($mapper, $data, $strategy);
        }
    }

    protected function value(Mapper $mapper, Field $field, array $data, FieldMappingStrategy $strategy)
    {
        if (($key = $strategy->getFieldLabel($mapper, $field)) !== null && isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    protected function read(Mapper $mapper, array $data, FieldMappingStrategy $strategy)
    {
        $primary = $this->value($mapper, $mapper->getPrimary(), $data, $strategy);

        if (null !== $primary && !isset($this->values[$mapper->getClass()][$mapper->getPrimary()->getName()])) {
            $this->values[$mapper->getClass()][$primary] = [];
            foreach ($mapper->getFields() as $name => $field) {
                $this->values[$mapper->getClass()][$primary][$field->getName()] = $this->value($mapper, $field, $data, $strategy);
            }
        }

        foreach ($mapper->associations() as $name => $association) {
            $this->read($association->getMapper(), $data, $strategy);
        }
    }

    public function getValuesOneEntity(Mapper $mapper)
    {
        if (isset($this->values[$mapper->getClass()]) && sizeof($this->values[$mapper->getClass()]) > 0) {
            return array_values($this->values[$mapper->getClass()])[0];
        }
        return [];
    }

    public function getValuesAllEntity(Mapper $mapper)
    {
        if (isset($this->values[$mapper->getClass()])) {
            return array_values($this->values[$mapper->getClass()]);
        }
        return [];
    }

}
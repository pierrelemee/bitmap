<?php

namespace Bitmap;

use PDOStatement;
use PDO;

class ResultSet
{
    protected $values;

    public function __construct(PDOStatement $statement, Mapper $mapper)
    {
        $this->values = [];

        // Should ask to a Strategy for the fetch mode
        while (false !== $data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $this->read($mapper, $data);
        }
    }

    protected function value(Mapper $mapper, Field $field, array $data)
    {
        if (isset($data[$mapper->getTable() . '.' . $field->getColumn()])) {
            return $data[$mapper->getTable() . '.' . $field->getColumn()];
        }
        return null;
    }

    protected function read(Mapper $mapper, array $data)
    {
        $primary = $this->value($mapper, $mapper->getPrimary(), $data);

        if (null !== $primary && !isset($this->values[$mapper->getClass()][$mapper->getPrimary()->getName()])) {
            $this->values[$mapper->getClass()][$primary] = [];
            foreach ($mapper->getFields() as $name => $field) {
                $this->values[$mapper->getClass()][$primary][$field->getName()] = $this->value($mapper, $field, $data);
            }
        }

        foreach ($mapper->associations() as $name => $association) {
            $this->read($association->getMapper(), $data);
        }
    }

    public function getValuesOneEntity(Mapper $mapper)
    {
        if (isset($this->values[$mapper->getClass()]) && sizeof($this->values[$mapper->getClass()]) > 0) {
            return array_values($this->values[$mapper->getClass()])[0];
        }
        return null;
    }

    public function getValuesAllEntity(Mapper $mapper)
    {
        if (isset($this->values[$mapper->getClass()])) {
            return array_values($this->values[$mapper->getClass()]);
        }
        return null;
    }

}
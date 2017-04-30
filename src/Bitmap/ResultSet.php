<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;
use PDOStatement;

class ResultSet
{
    /**
     * @var array
     */
    protected $values;
    protected $entities;

    public function __construct(PDOStatement $statement, Mapper $mapper, FieldMappingStrategy $strategy, Context $context)
    {
        $this->values = [];

        // Should ask to a Strategy for the fetch mode
        while (false !== $data = $statement->fetch($strategy->getPdoFetchingType())) {
            $this->read($mapper, $data, $strategy, $context);
        }
    }

    protected function value(Mapper $mapper, Field $field, array $data, FieldMappingStrategy $strategy, $depth = 0)
    {
        if (($key = $strategy->getFieldLabel($mapper, $field, $depth)) !== null && isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    protected function read(Mapper $mapper, array $data, FieldMappingStrategy $strategy, Context $context, $depth = 0)
    {
        $primary = $this->value($mapper, $mapper->getPrimary(), $data, $strategy, $depth);

        if (null !== $primary && !isset($this->values[$mapper->getClass()][$mapper->getPrimary()->getName()])) {
            $this->values[$mapper->getClass()][$depth][$primary] = [];
            foreach ($mapper->getFields() as $name => $field) {
                $this->values[$mapper->getClass()][$depth][$primary][$field->getName()] = $this->value($mapper, $field, $data, $strategy, $depth);
            }
        }

        foreach ($mapper->associations() as $name => $association) {
            if ($context->hasDependency($association->getName())) {
                $this->read($association->getMapper(), $data, $strategy, $context->getDependency($association->getName()), $depth + ($mapper->getClass() === $association->getMapper() ? 1 : 0));
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

    public function addEntity(Mapper $mapper, $primary, Entity $entity)
    {
        $this->entities[$mapper->getClass()][$primary] = $entity;
    }

    public function addEntities(Mapper $mapper, array $entities)
    {
    }

    public function getEntity(Mapper $mapper, $primary)
    {
        return isset($this->entities[$mapper->getClass()][$primary]) ? $this->entities[$mapper->getClass()][$primary] : null;
    }

    public function getValuesAllEntity(Mapper $mapper, $depth = 0)
    {
        if (isset($this->values[$mapper->getClass()][$depth])) {
            return array_values($this->values[$mapper->getClass()][$depth]);
        }

        return [];
    }

}
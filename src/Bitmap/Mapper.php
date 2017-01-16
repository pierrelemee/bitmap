<?php

namespace Bitmap;

use Exception;

class Mapper
{
    protected $class;
    protected $table;
    /**
     * @var Field
     */
    protected $primary;
    /**
     * @var Field[]
     */
    protected $fieldsByName;
    /**
     * @var Field[]
     */
    protected $fieldsByColumn;
    /**
     * @var Association[]
     */
    protected $associations;

    public function __construct($class, $table = null)
    {
        $this->class = $class;
        $this->table = $table ? : ($index = strrpos($class, '\\')) !== false ? substr($class, $index + 1) : $class;
        $this->fieldsByName = [];
        $this->fieldsByColumn = [];
        $this->associations = [];
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        if (null === $table) {
            throw new Exception(sprintf("No table name specified for %s' mapper", $this->class));
        }

        $this->table = $table;
        return $this;
    }

    /**
     * @return string mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param Field $field
     * @param boolean $primary
     *
     * @return Mapper
     */
    public function addField(Field $field, $primary = false)
    {
        // TODO: check for existence
        $this->fieldsByName[$field->getName()] = $field;
        $this->fieldsByColumn[$field->getColumn()] = $field;

        if ($primary) {
            $this->primary = $this->fieldsByName[$field->getName()];
        }

        return $this;
    }

    /**
     * @param $name
     * @return boolean
     */
    public function hasField($name)
    {
        return isset($this->fieldsByName[$name]);
    }

    /**
     * @param $column
     * @return boolean
     */
    public function hasFieldByColumn($column)
    {
        return isset($this->fieldsByColumn[$column]);
    }

    /**
     * @param $name
     * @return Field
     */
    public function getField($name)
    {
        return $this->fieldsByName[$name];
    }

    /**
     * @param $column
     * @return Field
     */
    public function getFieldByColumn($column)
    {
        return $this->fieldsByColumn[$column];
    }

    /**
     *
     */
    protected function values(Entity $entity)
    {
        $values = [];
        foreach ($this->fieldsByName as $field) {
            $values[$field->getName()] = $field->get($entity);
        }

        return $values;
    }

    protected function sqlValues(array $values, $delimiter = ", ")
    {
        $sql = [];

        foreach ($values as $name => $value) {
            $sql[] = sprintf("`%s` = %s", $name, $value);
        }

        return implode($delimiter, $sql);
    }

    public function hash(Entity $entity)
    {
        return sha1(implode(":", array_values($this->values($entity))));
    }

    protected function insertQuery(Entity $entity)
    {
        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->table,
            implode(", ", array_keys($this->fieldsByColumn)),
            implode(", ", $this->values($entity))
        );
    }

    public function insert(Entity $entity, $connection = null)
    {
        return Bitmap::connection($connection)->exec($this->insertQuery($entity)) > 0;
    }

    protected function updateQuery(Entity $entity)
    {
        return sprintf(
            "update `%s` set %s where `%s` = %s",
            $this->table,
            $this->sqlValues($this->values($entity)),
            $this->primary->getName(),
            $this->primary->get($entity)
        );
    }

    public function update(Entity $entity, $connection = null)
    {
        if (null !== $this->primary) {
            return Bitmap::connection($connection)->exec($this->updateQuery($entity)) > 0;
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    protected function deleteQuery(Entity $entity)
    {
        return sprintf(
            "delete from `%s` where `%s` = %s",
            $this->table,
            $this->primary->getName(),
            $this->primary->get($entity)
        );
    }

    public function delete(Entity $entity, $connection = null)
    {
        if (null !== $this->primary) {
            return Bitmap::connection($connection)->exec($this->deleteQuery($entity)) > 0;
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    /**
     * @param array $data
     * @return Entity
     */
    public function load(array $data)
    {
        /** @var $entity Entity */
        $entity = new $this->class();
        foreach ($data as $key => $value) {
            if ($this->hasFieldByColumn($key)) {
                $this->getFieldByColumn($key)->set($entity, $value);
            }
        }

        $entity->setBitmapHash($this->hash($entity));

        return $entity;
    }

    /**
     * @param $class
     * @return Mapper
     */
    public static function of($class)
    {
        return new Mapper(is_object($class) ? get_class($class) : $class);
    }
}
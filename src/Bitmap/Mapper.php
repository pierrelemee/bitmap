<?php

namespace PierreLemee\Bitmap;

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
    protected $fields;

    public function __construct($class, $table = null)
    {
        $this->class = $class;
        $this->table = $table ? : ($index = strrpos($class, '\\')) !== false ? substr($class, $index + 1) : $class;
        $this->fields = [];
    }

    public function setTable($table)
    {
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
        $this->fields[$field->getName()] = $field;

        if ($primary) {
            $this->primary = $this->fields[$field->getName()];
        }

        return $this;
    }

    /**
     * @param $name
     * @return boolean
     */
    public function hasField($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * @param $name
     * @return Field
     */
    public function getField($name)
    {
        return $this->fields[$name];
    }

    /**
     *
     */
    protected function values(Entity $entity)
    {
        $values = [];
        foreach ($this->fields as $field) {
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
            implode(", ", array_keys($this->fields)),
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

    /**
     * @param array $data
     * @return Entity
     */
    public function load(array $data)
    {
        $entity = new $this->class();
        foreach ($data as $key => $value) {
            if ($this->hasField($key)) {
                $this->getField($key)->set($entity, $value);
            }
        }

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
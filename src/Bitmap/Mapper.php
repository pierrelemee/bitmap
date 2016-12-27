<?php

namespace PierreLemee\Bitmap;

class Mapper
{
    protected $class;
    protected $table;
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
     * @return Mapper
     */
    public function addField(Field $field)
    {
        // TODO: check for existence
        $this->fields[$field->getName()] = $field;
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

    public function hash(Entity $entity)
    {
        return sha1(implode(":", array_values($this->values($entity))));
    }

    protected function insertQuery(Entity $entity)
    {
        return sprintf("insert into `%s` (%s) values (%s)", $this->table, implode(", ", array_keys($this->fields)), implode(", ", $this->values($entity)));
    }

    public function insert(Entity $entity, $connection = null)
    {
        return Bitmap::connection($connection)->exec($this->insertQuery($entity)) > 0;
    }

    public function update(Entity $entity, $connection = null)
    {
        return Bitmap::connection($connection)->exec(sprintf("update from `%s` set ?? where ??", $this->table));
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
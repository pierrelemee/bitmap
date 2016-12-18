<?php

namespace PierreLemee\Bitmap;

class Mapper
{
    protected $class;
    protected $table;
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
     * @param $class
     * @return Mapper
     */
    public static function of($class)
    {
        return new Mapper(is_object($class) ? get_class($class) : $class);
    }
}
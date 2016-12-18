<?php

namespace PierreLemee\Bitmap;

abstract class Field
{
    protected $name;
    protected $type;

    public function __construct($name, $type, $class)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function get(Entity $entity)
    {
        return $this->getValue(Bitmap::getTransformer($this->type)->fromObject($entity));
    }

    public abstract function getValue(Entity $entity);

    public function set(Entity $entity, $value)
    {
        $this->setValue($entity, Bitmap::getTransformer($this->type)->toObject($value));
    }

    public abstract function setValue(Entity $entity, $value);
}
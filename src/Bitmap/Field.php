<?php

namespace PierreLemee\Bitmap;

abstract class Field
{
    protected $name;

    public function __construct($name, $class)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public abstract function get(Entity $entity);

    public abstract function set(Entity $entity, $value);
}
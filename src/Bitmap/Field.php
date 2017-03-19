<?php

namespace Bitmap;

abstract class Field
{
    protected $name;
    protected $column;
    /**
     * @var Transformer
     */
    protected $transformer;
    /**
     * @var boolean
     */
    protected $incremented;
    /**
     * @var boolean
     */
    protected $nullable;
    /**
     * @var mixed default value
     */
    protected $default;

    public function __construct($name, $column = null)
    {
        $this->name = $name;
        $this->column = $column ? : $this->name;
        $this->incremented = false;
        $this->nullable = true;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }

    /**
     * @return Transformer
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @param Transformer $transformer
     *
     * @return Field
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIncremented()
    {
        return $this->incremented;
    }

    /**
     * @param bool $incremented
     *
     * @return Field
     */
    public function setIncremented($incremented)
    {
        $this->incremented = $incremented;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     *
     * @return Field
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasDefault()
    {
        return null !== $this->default;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return Field
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    public function get(Entity $entity)
    {
        return $this->transformer->fromObject($this->getValue($entity));
    }

    public abstract function getValue(Entity $entity);

    public function set(Entity $entity, $value)
    {
        $this->setValue($entity, $this->transformer->toObject($value));
    }

    public abstract function setValue(Entity $entity, $value);
}
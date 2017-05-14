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

    public function __construct($name, $type = Bitmap::TYPE_STRING, $column = null, $nullable = false)
    {
        $this->name = $name;

        if ($type instanceof Transformer) {
        	$this->setTransformer($type);
        } else {
        	$this->setType($type);
        }

        $this->column = $column ? : $name;
        $this->incremented = false;
        $this->nullable = $nullable;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
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
     * @param string $type
     *
     * @return Field
     */
    public function setType($type)
    {
        return $this->setTransformer(Bitmap::getTransformer($type));
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
        $value = $this->getValue($entity);
        return null !== $value ? $this->transformer->fromObject($value) : null;
    }

    public abstract function getValue(Entity $entity);

    public function set(Entity $entity, $value)
    {
        $this->setValue($entity, $this->transformer->toObject($value));
    }

    public abstract function setValue(Entity $entity, $value);
}
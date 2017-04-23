<?php

namespace Bitmap\Query\Context;

use Bitmap\Mapper;

class Context
{
    protected $dependencies;
    /**
     * @var Context
     */
    protected $parent;

    public function __construct($with = null, $parent = null)
    {
        $this->dependencies = [];
        $this->parent = $parent;

        if ($with) {
            $this->addDependency($with);
        }
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function getDepth($name)
    {
        if (null !== $this->parent) {
            return $this->parent->hasDependency($name) ? 1 + $this->parent->getDepth($name): 0;
        }

        return 0;
    }

    public function addDependency($with, $sub = null)
    {
        if (is_array($with)) {
            foreach ($with as $key => $value) {
                $this->addDependency($key, $value);
            }
        } else if (is_int($with)) {
            $this->dependencies[$sub] = new Context(null, $this);
        } else {
            $this->dependencies[$with] = new Context($sub, $this);
        }
    }

    public function hasDependency($name)
    {
        return isset($this->dependencies[$name]);
    }

    public function getDependency($name)
    {
        return isset($this->dependencies[$name]) ? $this->dependencies[$name] : new Context();
    }

    public static function fromContext($context, $parent = null)
    {
        if (null !== $context && $context instanceof Context) {
            return $context;
        }

        return new Context($context, $parent);
    }

    public static function fromMapper(Mapper $mapper, $parent = null)
    {
        return new Context(array_keys($mapper->associations()), $parent);
    }
}
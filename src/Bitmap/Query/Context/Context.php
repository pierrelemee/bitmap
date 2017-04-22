<?php

namespace Bitmap\Query\Context;

use Bitmap\Mapper;

class Context
{
    protected $dependencies;

    public function __construct($with = null)
    {
        $this->dependencies = [];

        if ($with) {
            $this->addDependency($with);
        }
    }

    public function addDependency($with, $sub = null)
    {
        if (is_array($with)) {
            foreach ($with as $key => $value) {
                $this->addDependency($key, $value);
            }
        } else if (is_int($with)) {
            $this->dependencies[$sub] = new Context();
        } else {
            $this->dependencies[$with] = new Context($sub);
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

    public static function fromContext($context)
    {
        if (null !== $context && $context instanceof Context) {
            return $context;
        }

        return new Context($context);
    }

    public static function fromMapper(Mapper $mapper)
    {
        return new Context(array_keys($mapper->associations()));
    }
}
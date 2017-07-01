<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;
use Bitmap\Mapper;
use Exception;
use Bitmap\FieldMappingStrategy;

abstract class Context
{
    /**
     * @var Mapper|null
     */
    protected $mapper;
    /**
     * @var Context[]
     */
    protected $dependencies;
    /**
     * @var Context
     */
    protected $parent;
    protected $depth;
    protected $depths;

    /**
     * Context constructor.
     *
     * @param Mapper $mapper
     * @param Context $parent
     *
     * @throws Exception
     */
    public function __construct($mapper = null, $parent = null)
    {
        $this->mapper       = $mapper;
        $this->dependencies = [];
        $this->parent       = $parent;

        if ($this->isRoot()) {
            $this->depths = [];
        }
    }

    public function getTableName()
    {
        return $this->mapper->getTable() . ($this->depth === 0 ? '' : $this->depth + 1);
    }

    /**
     * @return string[]
     */
    public abstract function getTables();

    /**
     * @param $strategy FieldMappingStrategy
     *
     * @return string[]
     */
    public abstract function getFields(FieldMappingStrategy $strategy);

    /**
     * @return string[]
     */
    public abstract function getJoins();

    /**
     * @return bool
     */
    protected function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * @return Context
     */
    protected function getRoot()
    {
        return $this->isRoot() ? $this : $this->parent->getRoot();
    }

    protected function findParentWithMapper(Mapper $mapper)
    {
        if ($this->mapper->equals($mapper)) {
            return $this;
        }

        return $this->isRoot() ? null : $this->parent->findParentWithMapper($mapper);
    }

    /**
     * @return Mapper|null
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * Returns absolute depth in the whole hierarchy
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Returns absolute depth in the whole hierarchy
     *
     * @return int
     */
    public function getHierarchyDepth()
    {
        return $this->isRoot() ? 0 : 1 + $this->parent->getDepth();
    }

    public function hasDependency($name)
    {
        return isset($this->dependencies[$name]);
    }

    public function getDependency($name)
    {
        return isset($this->dependencies[$name]) ? $this->dependencies[$name] : null;
    }

    /**
     * @return Context[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    public function toArray()
    {
        return [
            "{$this->getMapper()->getTable()}[{$this->depth}]" => count($this->dependencies) > 0 ?
                call_user_func_array(
                "array_merge",
                    array_map(function ($dependency) {
                        return $dependency->toArray();
                    }, array_values($this->dependencies)
                    )
                ) :
                []
        ];
    }

    public function __toString()
    {
        return str_repeat("\t", $this->getHierarchyDepth()) .
            "{$this->getMapper()->getTable()}[{$this->depth}]" . PHP_EOL .
            implode("", array_map(function($dependecy) {return $dependecy->__toString(); }, $this->dependencies)
        );
    }
}
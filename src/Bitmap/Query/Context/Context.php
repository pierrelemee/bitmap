<?php

namespace Bitmap\Query\Context;

use Bitmap\Mapper;

class Context
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
     * @param Mapper $mapper
     * @param null|array $with
     * @param Context $parent
     * @param int $depth
     */
    public function __construct($mapper = null, $with = null, $parent = null, $depth = 0)
    {
        $this->mapper = $mapper;
        $this->dependencies = [];
        $this->parent = $parent;

        if ($this->isRoot()) {
            $this->depths = [];
        }

        $this->depth = $this->getMapperDepth($this->mapper);

        if (null !== $mapper) {
            foreach ($mapper->associations() as $association) {
                if (!is_array($with) || is_int(array_search($association->getName(), $with)) || isset($with[$association->getName()])) {
                    $sub = !$this->hasCircularReference($association->getMapper()) ? (isset($with[$association->getName()]) && is_array($with[$association->getName()]) ? $with[$association->getName()] : null) : [];
                    $this->dependencies[$association->getName()] = new Context($association->getMapper(), $sub, $this);
                }
            }
        }
    }

    public function getTableName()
    {
        return $this->mapper->getTable() . ($this->depth === 0 ? '' : $this->depth + 1);
    }

    public function getTables()
    {
        $tables = [$this->getTableName()];

        foreach ($this->dependencies as $dependency) {
            $tables = array_merge($tables, $dependency->getTables());
        }
        return $tables;
    }

    public function getJoins()
    {
        $joins = [];

        foreach ($this->dependencies as $name => $dependency) {
            $joins = array_merge(
                $joins,
                $this->mapper->getAssociation($name)->joinClauses($this->mapper, $dependency->getTableName()),
                $dependency->getJoins()
            );
        }
        return $joins;
    }

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
        return $this->parent !== null ? 1 + $this->parent->getDepth() : 0;
    }

    /**
     * Returns depth in the hierarchy filtered by this mapper only
     *
     * @return int
     */
    public function getMapperDepth($mapper = null)
    {
        $mapper = $mapper ? : $this->mapper;
        if (!isset($this->getRoot()->depths[$mapper->getClass()])) {
            $this->getRoot()->depths[$mapper->getClass()] = 0;
        } else {
            $this->getRoot()->depths[$mapper->getClass()]++;
        }

        return $this->getRoot()->depths[$mapper->getClass()];
    }

    /**
     * @param Mapper $mapper
     *
     * @return int
     */
    protected function getMapperParentDistance(Mapper $mapper)
    {
        if ($this->mapper->equals($mapper)) {
            return 1;
        }

        return $this->parent !== null ? 1 + $this->parent->getMapperParentDistance($mapper) : 0;
    }

    protected function hasCircularReference(Mapper $mapper)
    {
        return $this->getMapperParentDistance($mapper) >= 1;
    }

    public function hasDependency($name)
    {
        return isset($this->dependencies[$name]);
    }

    public function getDependency($name)
    {
        return isset($this->dependencies[$name]) ? $this->dependencies[$name] : new Context();
    }

    /**
     * @return Context[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    public function __toString()
    {
        $res = $this->mapper->getClass() ." [{$this->depth}] " . (sizeof($this->dependencies) > 0 ? " =>" : "") .  "\n" ;
        foreach ($this->dependencies as $name => $dependency) {
            $res .= str_repeat("\t", $this->getHierarchyDepth()) . "'" .$name . "' : " . $dependency->__toString();
        }

        return $res;
    }
}
<?php

namespace Bitmap\Query\Context;

use Bitmap\Field;
use Exception;

class LoadContext extends Context
{
    protected $depth;
    protected $depths;

    public function __construct($mapper = null, $with = null, $parent = null)
    {
        parent::__construct($mapper, $parent);

        if ($this->isRoot()) {
            $this->depths = [];
        }

        if (!isset($this->getRoot()->depths[$this->mapper->getClass()])) {
            $this->getRoot()->depths[$this->mapper->getClass()] = 0;
        } else {
            $this->getRoot()->depths[$this->mapper->getClass()]++;
        }

        $this->depth = $this->getRoot()->depths[$this->mapper->getClass()];

        if (is_array($with)) {
            foreach ($with as $name => $value) {
                if (is_int($name)) {
                    $name = $value;
                    $subcontext = [];
                } else {
                    $subcontext = $value;
                }
                if (strpos($name, '@') === 0) {
                    $association = $this->getMapper()->getAssociation(substr($name, 1));
                    if (null !== $association) {
                        if (null === $source = $this->findParentWithMapper($association->getMapper())) {
                            throw new Exception("Undefined source mapper in hierarchy for reference $name");
                        }
                        $this->dependencies[$association->getName()] = new ReferenceContext($association->getMapper(), $source, $this);
                    }
                } else {
                    $association = $this->getMapper()->getAssociation($name);
                    if (null !== $association) {
                        $this->dependencies[$association->getName()] = new LoadContext($association->getMapper(), $subcontext, $this);
                    }
                }
            }
        }

        if (is_null($with)) {
            foreach ($this->mapper->associations() as $association) {
                if (!isset($this->dependencies[$association->getName()])) {
                    if ($association->isAutoloaded()) {
                        $this->dependencies[$association->getName()] = new LoadContext($association->getMapper(), [], $this);
                    }
                }
            }
        }
    }

    public function getTableName()
    {
        return $this->mapper->getTable() . ($this->depth === 0 ? '' : $this->depth + 1);
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

    public function getTables()
    {
        $tables = [$this->getTableName()];

        foreach ($this->dependencies as $dependency) {
            $tables = array_merge($tables, $dependency->getTables());
        }
        return $tables;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->mapper->getFields();
    }

    public function getJoins()
    {
        $joins = [];

        foreach ($this->dependencies as $name => $dependency) {

            foreach ($dependency->getJoinsOn($this, $name) as $join) {
                $joins[] = $join;
            }

            foreach ($dependency->getJoins() as $join) {
                $joins[] = $join;
            }
        }

        return $joins;
    }

    protected function children($mapper, $parent = null, $with = null)
    {
        return new LoadContext($mapper, $with, $parent);
    }

    protected function getJoinsOn(LoadContext $context, $name)
    {
        return $context->mapper->getAssociation($name)->joinClauses($context->mapper, $this->getTableName());
    }


}
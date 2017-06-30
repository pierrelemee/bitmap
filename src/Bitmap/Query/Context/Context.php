<?php

namespace Bitmap\Query\Context;

use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use Exception;

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
     *
     * @param Mapper $mapper
     * @param null|array $with
     * @param Context $parent
     * @param int $depth
     *
     * @throws Exception
     */
    public function __construct($mapper = null, $with = null, $parent = null, $depth = null)
    {
        $this->mapper = $mapper;
        $this->dependencies = [];
        $this->parent = $parent;

        if ($this->isRoot()) {
            $this->depths = [];
        }

        $this->depth = (null !== $depth) ? $depth : $this->calculateMapperDepth();

        $this->initialize($with);
    }

    protected function initialize($with = null)
    {
        foreach ($this->mapper->associations() as $association) {
            if (is_array($with)) {
                if (is_int(array_search($name = $association->getName(), $with)) || isset($with[$name])) {
                    $this->dependencies[$association->getName()] = new Context($association->getMapper(), isset($with[$name]) ? $with[$name] : null, $this);
                } else if (is_int(array_search($name = "@{$association->getName()}", $with)) || isset($with[$name])) {
                    echo "Reference $name\n";
                    if (null === $source = $this->findParentWithMapper($association->getMapper())) {
                        throw new Exception("Undefined source mapper in hierarchy for reference $name");
                    }

                    $this->dependencies[$association->getName()] = new ReferenceContext($association->getMapper(), $source, $this);
                }
            }
            if (is_null($with)){
                // TODO apply association's default loading instead
                //if ($association->hasLocalValue()) {
                $this->dependencies[$association->getName()] = new Context($association->getMapper(), [], $this);
                //}
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

    public function getFields(FieldMappingStrategy $strategy)
    {
        $fields = [];

        foreach ($this->mapper->getFields() as $name => $field) {
            $fields[] = sprintf(
                "`%s`.`%s` as `%s`",
                $this->getTableName(),
                $field->getColumn(),
                $strategy->getFieldLabel($this->mapper, $field->getColumn(), $this->depth)
            );
        }

        foreach ($this->dependencies as $dependency) {
            $fields = array_merge($fields, $dependency->getFields($strategy));
        }

        return $fields;
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
        return $this->parent !== null ? 1 + $this->parent->getDepth() : 0;
    }

    /**
     * Returns depth in the hierarchy filtered by this mapper only
     *
     * @return int
     */
    protected function calculateMapperDepth()
    {
        if (!isset($this->getRoot()->depths[$this->mapper->getClass()])) {
            $this->getRoot()->depths[$this->mapper->getClass()] = 0;
        } else {
            $this->getRoot()->depths[$this->mapper->getClass()]++;
        }

        return $this->getRoot()->depths[$this->mapper->getClass()];
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
        return json_encode($this->toArray());
    }
}
<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;
use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use Exception;

abstract class QueryContext extends Context
{
    public function __construct($mapper, $with = null, $parent = null)
    {
        parent::__construct($mapper, $parent);
        if (!isset($this->getRoot()->depths[$this->mapper->getClass()])) {
            $this->getRoot()->depths[$this->mapper->getClass()] = 0;
        } else {
            $this->getRoot()->depths[$this->mapper->getClass()]++;
        }

        $this->depth = $this->getRoot()->depths[$this->mapper->getClass()];

        foreach ($this->mapper->associations() as $association) {
            if (is_array($with)) {
                if (is_int(array_search($name = $association->getName(), $with)) || isset($with[$name])) {
                    $this->dependencies[$association->getName()] = $this->children($association->getMapper(), $this, isset($with[$name]) ? $with[$name] : null);
                } else if (is_int(array_search($name = "@{$association->getName()}", $with)) || isset($with[$name])) {
                    if (null === $source = $this->findParentWithMapper($association->getMapper())) {
                        throw new Exception("Undefined source mapper in hierarchy for reference $name");
                    }

                    $this->dependencies[$association->getName()] = new ReferenceContext($association->getMapper(), $source, $this);
                }
            }
            if (is_null($with)){
                if ($this->isAssociationDefaultIncluded($association)) {
                    $this->dependencies[$association->getName()] = $this->children($association->getMapper(), $this, []);
                }
            }
        }
    }

    /**
     * @param Association $association
     *
     * @return boolean
     */
    protected abstract function isAssociationDefaultIncluded(Association $association);

    /**
     * @param Mapper $mapper
     * @param Context $parent
     * @param null|array $with
     *
     * @return boolean
     */
    protected abstract function children($mapper, $parent = null, $with = null);

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
}
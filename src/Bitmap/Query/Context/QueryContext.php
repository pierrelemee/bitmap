<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;
use Bitmap\Field;
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
                        $this->dependencies[$association->getName()] = $this->children($association->getMapper(), $this, $subcontext);
                    }
                }
            }
        }

        if (is_null($with)) {
            foreach ($this->mapper->associations() as $association) {
                if (!isset($this->dependencies[$association->getName()])) {
                    if ($this->isAssociationDefaultIncluded($association)) {
                        $this->dependencies[$association->getName()] = $this->children($association->getMapper(), $this, []);
                    }
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
            $joins = array_merge(
                $joins,
                $this->mapper->getAssociation($name)->joinClauses($this->mapper, $dependency->getTableName()),
                $dependency->getJoins()
            );
        }
        return $joins;
    }
}
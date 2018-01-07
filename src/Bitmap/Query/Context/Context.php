<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;
use Bitmap\Field;
use Bitmap\Mapper;
use Exception;
use Bitmap\FieldMappingStrategy;
use PDO;

abstract class Context
{
    /**
     * @var Mapper|null
     */
    protected $mapper;
    /**
     * @var static[]
     */
    protected $dependencies;
    /**
     * @var static
     */
    protected $parent;


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
    }

    /**
     * @return bool
     */
    protected function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * @return static
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

    public function hasDependency($name)
    {
        return isset($this->dependencies[$name]);
    }

    public function getDependency($name)
    {
        return isset($this->dependencies[$name]) ? $this->dependencies[$name] : null;
    }

    /**
     * @return static[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
}
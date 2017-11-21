<?php

namespace Bitmap;

use Bitmap\Query\Clauses\Join;

abstract class Association
{
    const OPTION_LOAD = 'load';
    const OPTION_SAVE = 'save';

    protected $name;
    protected $class;
    protected $column;
    protected $autoload;
    protected $autosave;

    public function __construct($name, $class, $column, $options = null)
    {
        $this->name = $name;
        $this->class = $class;
        $this->column = $column;

        $this->autoload = is_array($options) ? in_array(self::OPTION_LOAD, $options) : $this->getDefaultAutoload();
        $this->autosave = is_array($options) ? in_array(self::OPTION_SAVE, $options) : $this->getDefaultAutosave();
    }

    public function isAutoloaded()
    {
        return $this->autoload;
    }

    public function isAutosaved()
    {
        return $this->autosave;
    }

    /**
     * @return bool
     */
    protected abstract function getDefaultAutoload();

    /**
     * @return bool
     */
    protected abstract function getDefaultAutosave();

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Mapper
     */
    public function getMapper()
    {
        return Bitmap::current()->getMapper($this->class);
    }

    /**
     * Indicates whether the association requires a value in a column of the source entity's table
     *
     * @return boolean
     */
    public abstract function hasLocalValue();

    /**
     * Return the list of join clauses from the class managed by the mapper $left
     *
     * @param Mapper $mapper
     * @param string $alias
     *
     * @return Join[]
     */
    public abstract function joinClauses(Mapper $mapper, $alias);

    protected function joinClause($tableFrom, $columnFrom, $tableTo, $aliasTableTo, $columnTo)
    {
        return " left join `{$tableTo}` {$aliasTableTo} on `{$tableFrom}`.`{$columnFrom}` = `{$aliasTableTo}`.`{$columnTo}`";
    }

    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public abstract function get(Entity $entity);

    /**
     * @param Entity $entity
     *
     * @return Entity[]
     */
    public abstract function getAll(Entity $entity);

    public abstract function set($value, Entity $entity);
}
<?php

namespace Bitmap;

use Bitmap\Query\Delete;
use Bitmap\Query\Insert;
use Bitmap\Query\Update;
use Exception;
use PDOStatement;

class Mapper
{
    protected $class;
    protected $table;
    /**
     * @var Field
     */
    protected $primary;
    /**
     * @var Field[]
     */
    protected $fieldsByName;
    /**
     * @var Field[]
     */
    protected $fieldsByColumn;
    /**
     * @var Association[]
     */
    protected $associations;

    public function __construct($class, $table = null)
    {
        $this->class = $class;
        $this->table = $table ? : ($index = strrpos($class, '\\')) !== false ? substr($class, $index + 1) : $class;
        $this->fieldsByName = [];
        $this->fieldsByColumn = [];
        $this->associations = [];
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        if (null === $table) {
            throw new Exception(sprintf("No table name specified for %s' mapper", $this->class));
        }

        $this->table = $table;
        return $this;
    }

    /**
     * @return string mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return boolean
     */
    public function hasPrimary()
    {
        return null !== $this->primary;
    }

    /**
     * @return Field
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @param Field $field
     * @param boolean $primary
     *
     * @return Mapper
     */
    public function addField(Field $field, $primary = false)
    {
        // TODO: check for existence
        $this->fieldsByName[$field->getName()] = $field;
        $this->fieldsByColumn[$field->getColumn()] = $field;

        if ($primary) {
            $this->primary = $this->fieldsByName[$field->getName()];
        }

        return $this;
    }

    /**
     * @param $name
     * @return boolean
     */
    public function hasField($name)
    {
        return isset($this->fieldsByName[$name]);
    }

    /**
     * @param $column
     * @return boolean
     */
    public function hasFieldByColumn($column)
    {
        return isset($this->fieldsByColumn[$column]);
    }

    /**
     * @param $name
     * @return Field
     */
    public function getField($name)
    {
        return $this->fieldsByName[$name];
    }

    /**
     * @param $column
     * @return Field
     */
    public function getFieldByColumn($column)
    {
        return $this->fieldsByColumn[$column];
    }

    public function getFields()
    {
        return $this->fieldsByName;
    }

    public function fieldNames()
    {
        $fields = [];
        foreach ($this->fieldsByName as $field) {
            $fields[] = $this->fieldName($field);
        }

        foreach ($this->associations as $association) {
            foreach ($association->getMapper()->fieldNames() as $name) {
                $fields[] = $name;
            }
        }

        return $fields;
    }

    public function fieldName(Field $field)
    {
        return sprintf("`%s`.`%s` as `%s.%s`", $this->table, $field->getColumn(), $this->table, $field->getColumn());
    }

    /**
     * @return Association[]
     */
    public function associations()
    {
        return $this->associations;
    }

    public function addAssociation(Association $association)
    {
        $this->associations[$association->getName()] = $association;
        return $this;
    }

    /**
     * @return Entity
     */
    public function createEntity()
    {
        return new $this->class();
    }

    /**
     *
     * @return array
     */
    public function values(Entity $entity)
    {
        $values = [];
        foreach ($this->fieldsByName as $field) {
            $values[$field->getName()] = $field->get($entity);
        }

        return $values;
    }

    public function hash(Entity $entity)
    {
        return sha1(implode(":", array_values($this->values($entity))));
    }

    /**
     * @param Entity $entity
     * @param null $connection
     * @return bool
     */
    public function insert(Entity $entity, $connection = null)
    {
        // Save all associated entities first:
        foreach ($this->associations as $association) {
            $association->get($entity)->save();
        }

        $sql = Insert::fromEntity($entity)->sql();
        $count = Bitmap::connection($connection)->exec($sql);

        if ($count  > 0) {
            if ($this->hasPrimary()) {
                $this->primary->set($entity, Bitmap::connection($connection)->lastInsertId());
            }
            return true;
        }

        return false;
    }

    public function update(Entity $entity, $connection = null)
    {
        if (null !== $this->primary) {

            // Save all associated entities first:
            foreach ($this->associations as $association) {
                $association->get($entity)->save();
            }

            $sql = Update::fromEntity($entity)->sql();
            $count = Bitmap::connection($connection)->exec($sql);
            return $count > 0;
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    public function delete(Entity $entity, $connection = null)
    {
        if ($this->hasPrimary()) {
            $sql = Delete::fromEntity($entity)->sql();
            return Bitmap::connection($connection)->exec($sql) > 0;
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    /**
     * @param ResultSet $result
     *
     * @return Entity
     */
    public function loadOne(ResultSet $result)
    {
        $entity = $this->createEntity();
        foreach ($result->getValuesOneEntity($this) as $name => $value) {
            $this->fieldsByName[$name]->set($entity, $value);
        }

        foreach ($this->associations as $association) {
            $association->set($result, $entity);
        }

        $entity->setBitmapHash($this->hash($entity));

        return $entity;
    }

    /**
     * @param ResultSet $result
     *
     * @return Entity[]
     */
    public function loadAll(ResultSet $result)
    {
        $entities = [];

        foreach ($result->getValuesAllEntity($this) as $data) {
            $entity = $this->createEntity();
            foreach ($data as $name => $value) {
                $this->fieldsByName[$name]->set($entity, $value);
            }

            foreach ($this->associations as $association) {
                $association->set($result, $entity);
            }

            $entity->setBitmapHash($this->hash($entity));
            $entities[] = $entity;
        }

        return $entities;
    }

    /**
     * @param $class
     * @return Mapper
     *
     * @throws Exception
     */
    public static function of($class)
    {
        if (null === $class) {
            throw new Exception("Can't find mapper of null class");
        }

        return new Mapper(is_object($class) ? get_class($class) : $class);
    }
}
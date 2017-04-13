<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;
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

    public function addPrimary(Field $field, $incremented = true, $nullable = false)
    {
        $field->setIncremented($incremented);
        $field->setNullable($nullable);
        $this->addField($field);
        $this->primary = $this->fieldsByName[$field->getName()];
        return $this;
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
     *
     * @return Mapper
     */
    public function addField(Field $field)
    {
        // TODO: check for existence
        $this->fieldsByName[$field->getName()] = $field;
        $this->fieldsByColumn[$field->getName()] = $field;

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

	public function hasAssociation($name)
	{
		return isset($this->associations[$name]);
	}

	public function getAssociation($name)
	{
		return isset($this->associations[$name]) ? $this->associations[$name] : null;
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
     * @param Context $context the list of association (by their names) to recursively save
     * @param null $connection
     *
     * @return bool
     *
     * @throws Exception
     */
    public function insert(Entity $entity, Context $context, $connection = null)
    {
        // Save all associated entities first:
        foreach ($this->associations as $association) {
        	if ($context->hasDependency($association->getName())) {
		        foreach ($association->getAll($entity) as $e) {
			        $e->save($context->getDependency($association->getName()), $connection);
		        }
	        }
        }

        $query = new Insert($entity, $context);
        $count = $query->execute(Bitmap::connection($connection));

        if ($count > 0) {
            if ($this->hasPrimary()) {
                $this->primary->set($entity, Bitmap::connection($connection)->lastInsertId());
            }
            return true;
        }

        return false;
    }

	/**
	 * @param Entity $entity
	 * @param null|array|Context $context the list of association (by their names) to recursively save
	 * @param null $connection
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
    public function update(Entity $entity, Context $context, $connection = null)
    {
        if (null !== $this->primary) {
            // Save all associated entities first:
            foreach ($this->associations as $association) {
                if ($context->hasDependency($association->getName())) {
		            foreach ($association->getAll($entity) as $e) {
			            $e->save($context->getDependency($association->getName()), $connection);
		            }
	            }
            }

            $query = new Update($entity, $context);
            $count = $query->execute(Bitmap::connection($connection));

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
    	if (sizeof($values = $result->getValuesOneEntity($this)) > 0) {
		    $entity = $this->createEntity();
		    foreach ($values as $name => $value) {
			    $this->fieldsByName[$name]->set($entity, $value);
		    }

		    foreach ($this->associations as $association) {
                if ($association->getClass() !== $this->class) {
                    $association->set($result, $entity);
                }
		    }

		    $entity->setBitmapHash($this->hash($entity));

		    return $entity;
	    }

	    return null;
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
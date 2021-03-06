<?php

namespace Bitmap;

use Bitmap\Associations\ManyToMany\Via;
use Bitmap\Associations\MethodAssociationManyToMany;
use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Associations\MethodAssociationOneToMany;
use Bitmap\Associations\PropertyAssociationManyToMany;
use Bitmap\Associations\PropertyAssociationOne;
use Bitmap\Associations\PropertyAssociationOneToMany;
use Bitmap\Exceptions\MapperException;
use Bitmap\Fields\MethodField;
use Bitmap\Fields\PropertyField;
use Bitmap\Query\Context\Context;
use Bitmap\Query\Delete;
use Bitmap\Query\Insert;
use Bitmap\Query\Update;
use Exception;
use ReflectionClass;

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
        $this->table = preg_replace('~[[:cntrl:]]~', '', trim($table ? : substr(strrchr($class, "\\"), 1)));
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
	 * @param string $name
	 * @param string|null $column
	 * @param string|Transformer $type
	 * @param string|null $getter
	 * @param string|null $setter
	 *
	 * @return Mapper
	 *
	 * @throws MapperException
	 */
	public function addPrimary($name, $type = null, $column = null, $getter = null, $setter = null)
	{
		return $this->addField($name, $type, $column, false, $getter, $setter, true);
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
	 * @param string|Field $name
	 * @param string|null $column
	 * @param string|Transformer $type
	 * @param bool $nullable
	 * @param string|null $getter
	 * @param string|null $setter
	 * @param bool $primary
	 *
	 * @return Mapper
	 *
	 * @throws MapperException
	 */
	public function addField($name, $type, $column = null, $nullable = false, $getter = null, $setter = null, $primary = false)
	{
        $field = null;

        if ($name instanceof Field) {
            $field = $name;
        } else {
            $column = $column ? : $name;
            $reflection = new ReflectionClass($this->class);

            if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
                $field = new PropertyField($name, $reflection->getProperty($name), $type, $column, $nullable);
            } else {
                if (null === $getter) {
                    $getter = MethodField::getterForName($name);
                    $setter = MethodField::setterForName($name);
                } else {
                    $setter = $setter ? : preg_replace("/^get/", "set", $getter);
                }

                if ($reflection->hasMethod($getter) && $reflection->hasMethod($setter)) {
                    $field = new MethodField($name, $reflection->getMethod($getter), $reflection->getMethod($setter), $type, $column, $nullable);
                } else {
                    throw new MapperException("Unable to create a field with name {$name}' to '{$this->class}'");
                }
            }
        }

        // TODO: check for existence
        $this->fieldsByName[$field->getName()] = $field;
        $this->fieldsByColumn[$field->getColumn()] = $field;

        if ($primary) {
            $field->setIncremented(true);
            $this->primary = $field;
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

    public function addAssociationOne($name, $class, $column = null, $getter = null, $setter = null, $options = null)
    {
        $column = $column ? : $name;
        $reflection = new ReflectionClass($this->class);

        if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
            return $this->addAssociation(new PropertyAssociationOne($name, $class, $reflection->getProperty($name), $column, $options));
        } else {
            if (null === $getter) {
                $getter = MethodField::getterForName($name);
                $setter = MethodField::setterForName($name);
            } else {
                $setter = $setter ? : preg_replace("/^get/", "set", $getter);
            }

            if ($reflection->hasMethod($getter) && $reflection->hasMethod($setter)) {
                return $this->addAssociation(new MethodAssociationOne($name, $class, $reflection->getMethod($getter), $reflection->getMethod($setter), $column, $options));
            } else {
                throw new MapperException("Unable to find association one for '{$reflection->getName()}' with name {$name}' to '{$class}'");
            }
        }
    }

    public function addAssociationOneToMany($name, $class, $column = null, $getter = null, $setter = null, $options = null)
    {
        $column = $column ? : $name;
        $reflection = new ReflectionClass($this->class);

        if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
            return $this->addAssociation(new PropertyAssociationOneToMany($name, $class, $reflection->getProperty($name), $column), $options);
        } else {
            if (null === $getter) {
                $getter = MethodField::getterForName($name);
                $setter = MethodField::setterForName($name);
            } else {
                $setter = $setter ? : preg_replace("/^get/", "set", $getter);
            }

            if ($reflection->hasMethod($getter) && $reflection->hasMethod($setter)) {
                return $this->addAssociation(new MethodAssociationOneToMany($name, $class, $reflection->getMethod($getter), $reflection->getMethod($setter), $column, $options));
            } else {
                throw new MapperException("Unable to find association one to many for '{$reflection->getName()}' with name {$name}' to '{$class}'");
            }
        }
    }

    public function addAssociationManyToMany($name, $class, Via $via, $column = null, $targetColumn = null, $getter = null, $setter = null)
    {
        if (null === $via->getSourceColumn()) {
            throw new MapperException("Missing source column in table {$via->getTable()} for association many-to-many with name {$name}' to '{$class}'");
        }

        if (null === $via->getSourceColumn()) {
            throw new MapperException("Missing target column in table {$via->getTable()} for association many-to-many with name {$name}' to '{$class}'");
        }


        $column = $column ? : $name;
        $reflection = new ReflectionClass($this->class);

        if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
            return $this->addAssociation(new PropertyAssociationManyToMany($name, $class, $reflection->getProperty($name), $column, $via, $targetColumn));
        } else {
            if (null === $getter) {
                $getter = MethodField::getterForName($name);
                $setter = MethodField::setterForName($name);
            } else {
                $setter = $setter ? : preg_replace("/^get/", "set", $getter);
            }

            if ($reflection->hasMethod($getter) && $reflection->hasMethod($setter)) {
                return $this->addAssociation(new MethodAssociationManyToMany($name, $class, $reflection->getMethod($getter), $reflection->getMethod($setter), $column, $via, $targetColumn));
            } else {
                throw new MapperException("Unable to find association many to many for '{$reflection->getName()}' with name {$name}' to '{$class}'");
            }
        }
    }

    /**
     * @return Entity
     */
    public function createEntity()
    {
        return new $this->class();
    }

    /**
     * @param $entity Entity
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
        	if ($association->hasLocalValue() && $context->hasDependency($association->getName())) {
		        foreach ($association->getAll($entity) as $e) {
			        $e->save($context->getDependency($association->getName()), $connection);
		        }
	        }
        }

        $query = new Insert($this, $entity, $context);
        $count = $query->execute(Bitmap::current()->connection($connection));

        if ($count > 0) {
	        if ($this->hasPrimary() && $this->getPrimary()->isIncremented()) {
                $this->primary->set($entity, Bitmap::current()->connection($connection)->lastInsertId());
            }
        }

        foreach ($this->associations as $association) {
            if (!$association->hasLocalValue() && $context->hasDependency($association->getName())) {
                foreach ($association->getAll($entity) as $e) {
                    $e->save($context->getDependency($association->getName()), $connection);
                }
            }
        }


        return $count > 0;
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

            $query = new Update($this, $entity, $context);
            $count = $query->execute(Bitmap::current()->connection($connection));

	        return $count > 0;
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    public function delete(Entity $entity, $connection = null)
    {
        if ($this->hasPrimary()) {
            $query = new Delete($entity);
            return $query->execute(Bitmap::current()->connection($connection));
        }

        throw new Exception("No primary declared for class {$this->class}");
    }

    /**
     * @param ResultSet $result
     * @param Context $context
     *
     * @return Entity
     */
    public function loadOne(ResultSet $result, Context $context)
    {
        $primaries = $result->getPrimaries($this);

	    return sizeof($primaries) > 0 ? $this->inflate($result, $context, $primaries[0]) : null;
    }

    /**
     * @param ResultSet $result
     * @param Context $context
     *
     * @return Entity[]
     */
    public function loadAll(ResultSet $result, Context $context)
    {
        $entities = [];

        foreach ($result->getPrimaries($this) as $primary) {
            $entity = $this->inflate($result, $context, $primary);

            $entities[] = $entity;
        }

        return $entities;
    }

    public function inflate(ResultSet $result, Context $context, $primary)
    {
	    if (null === $entity = $result->getEntity($this, $primary)) {
		    $entity = $this->createEntity();

		    if (null !== $values = $result->getValuesEntity($this, $primary, $context->getDepth())) {

			    foreach ($this->fieldsByName as $name => $field) {
				    $field->set($entity, $values[$name]);
			    }

			    $result->addEntity($this, $primary, $entity);

			    foreach ($this->associations as $association) {
				    if ($context->hasDependency($association->getName())) {
					    if ($association->hasLocalValue()) {
						    if (null !== $s = $association->getMapper()->inflate($result, $context->getDependency($association->getName()), $values[$association->getName()][0])) {
							    $association->set($s, $entity);
						    }
					    } else {
                            if (sizeof($values[$association->getName()]) > 0) {
                                $e = [];
                                foreach ($values[$association->getName()] as $p) {
                                    // TODO: find the way to choose if you want to include null values or not
                                    if (null !== $s = $association->getMapper()->inflate($result, $context->getDependency($association->getName()), $p)) {
                                        $e[] = $s;
                                    }
                                }

                                $association->set($e, $entity);
                            }
					    }
				    }
			    }

			    $entity->setBitmapHash(base64_encode(serialize($this->values($entity))));

			    return $entity;
		    }

		    return null;
	    }

        return $entity;
    }

    public function equals(Mapper $right)
    {
        return $this->class == $right->class;
    }

    /**
     * @param $class
     * @return Mapper
     *
     * @throws Exception
     */
    public static function from($class)
    {
        if (null === $class) {
            throw new Exception("Can't find mapper of null class");
        }

        return new Mapper(is_object($class) ? get_class($class) : $class);
    }
}
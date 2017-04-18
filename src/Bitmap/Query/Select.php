<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Strategies\PrefixStrategy;
use Bitmap\Mapper;
use PDO;
use Bitmap\FieldMappingStrategy;
use Bitmap\ResultSet;
use Bitmap\Entity;
use Exception;

class Select extends Query
{
    /**
     * @var FieldMappingStrategy
     */
    protected $strategy;
    protected $where;
    protected $with;
    protected $tables;
    protected $fields;
    protected $joins;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->where = [];
        $this->with = [];
        $this->tables = [];
        $this->fields = [];
        $this->joins = [];
        $this->strategy = new PrefixStrategy();
    }

    public function execute(PDO $connection)
    {
        $sql = $this->sql();
        return $connection->query($sql, $this->strategy->getPdoFetchingType());
    }

    /**
     * @param null $connection
     * @param array $with
     *
     * @return Entity|null
     */
    public function one($with = [], $connection = null)
    {
        $this->with = $with;
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $with);

        return $this->mapper->loadOne($result);
    }

    /**
     * @param null $connection
     *
     * @return Entity[]
     */
    public function all($connection = null)
    {
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy);

        return $this->mapper->loadAll($result);
    }

    public static function fromClass($class)
    {
        return new Select(Bitmap::getMapper(is_object($class) ? get_class($class) : $class));
    }

    public function where($field, $operation, $value)
    {
    	if ($this->mapper->hasField($field)) {
		    $this->where[] = sprintf(
			    "`%s`.`%s` %s %s",
			    $this->mapper->getTable(),
			    $this->mapper->getField($field)->getName(),
			    $operation,
			    $this->mapper->getField($field)->getTransformer()->fromObject($value)
		    );

		    return $this;
	    }

	    if ($this->mapper->hasAssociation($field)) {
    		$association = $this->mapper->getAssociation($field);

    		if ($association->hasLocalValue()) {
			    $this->where[] = sprintf(
				    "`%s`.`%s` %s %s",
				    $this->mapper->getTable(),
				    $association->getName(),
				    $operation,
				    $value
			    );
		    }

		    return $this;
	    }

	    throw new Exception("No field with name '{$field}'");
    }

    protected function joinClauses($mapper = null)
    /**
     * @param $mapper Mapper
     * @param $with array
     *
     * @return array
     */
    protected function joinClauses($mapper, $with)
    {
        $joins = [];

        foreach ($mapper->associations() as $association) {
            if (in_array($association->getName(), $with)) {
                if (!isset($counters[$association->getName()])) {
                    $counters[$association->getName()] = 0;
                } else {
                    $counters[$association->getName()]++;
                }

                $joins = array_merge($joins, $association->joinClauses($mapper, $counters[$association->getName()]));
            }
        }

        foreach ($mapper->associations() as $association) {
            if (isset($with[$association->getName()])) {
                $joins = array_merge($joins, $this->joinClauses($association->getMapper(), is_array($with[$association->getName()]) ? $with[$association->getName()] : [], $counters));
            }
        }

        return $joins;
    }

    /**
     * @param Mapper $mapper
     *
     * @return array
     */
    protected function fields($mapper)
    {
        $fields = [];
        foreach ($mapper->getFields() as $field) {
            $fields[] = "`{$mapper->getTable()}`.`{$field->getName()}` as `{$this->strategy->getFieldLabel($mapper, $field)}`";
        }

        /*
        foreach ($mapper->associations() as $association) {
            if ($this->mapper->getClass() !== $mapper->getClass()) {
                $fields = array_merge($fields, $this->fields($association->getMapper()));
            }
        }
        */

        return $fields;
    }

    protected function tables(Mapper $mapper, $with = [])
    {
        if (!isset($this->tables[$mapper->getTable()])) {
            $this->tables[$mapper->getTable()] = 0;
        } else {
            $this->tables[$mapper->getTable()]++;
        }

        $index = $this->tables[$mapper->getTable()];

        foreach ($mapper->getFields() as $field) {
            $this->fields[] = sprintf(
                "`%s`.`%s` as `%s`",
                $mapper->getTable() . ($index > 0 ? $index : ''),
                $field->getName(),
                $this->strategy->getFieldLabel($mapper, $field, $index)
            );
        }

        foreach ($mapper->associations() as $name => $association) {
            if (isset($with[$association->getName()])) {
                $this->joins = array_merge($this->joins, $association->joinClauses($mapper->getTable() . ($this->tables[$mapper->getTable()] > 0 ? $this->tables[$mapper->getTable()] : ''), $this->tables[$association->getMapper()->getTable()] + 1));
                $this->tables($association->getMapper(), is_array($with[$association->getName()]) ? $with[$association->getName()] : []);
            }
        }

        /*
        foreach ($mapper->associations() as $name => $association) {
            if (isset($with[$name])) {

            }

            if (!isset($tables[$association->getMapper()->getTable()])) {

            }
        }
        */
    }

    public function sql()
    {
        $this->tables($this->mapper, $this->with);

        return sprintf("select %s from %s %s",
            implode(", ", $this->fields),
            $this->mapper->getTable() . (implode("", $this->joins)),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : ""
        );
    }
}
<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Strategies\PrefixStrategy;
use Bitmap\Mapper;
use PDO;
use Bitmap\FieldMappingStrategy;
use Bitmap\ResultSet;
use Exception;

class Select extends Query
{
    /**
     * @var FieldMappingStrategy
     */
    protected $strategy;
    protected $where;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->where = [];
        $this->strategy = new PrefixStrategy();
    }

    public function execute(PDO $connection)
    {
        $sql = $this->sql();
        return $connection->query($sql, $this->strategy->getPdoFetchingType());
    }

    /**
     * @param null $connection
     *
     * @return Entity|null
     */
    public function one($connection = null)
    {
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy);

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
    {
        $joins = [];
        $mapper = $mapper ? : $this->mapper;
        foreach ($mapper->associations() as $association) {
            $joins = array_merge($joins, $association->joinClauses($mapper));
        }
        foreach ($mapper->associations() as $association) {
            $joins = array_merge($joins, $this->joinClauses($association->getMapper()));
        }

        return $joins;
    }

    /**
     * @param Mapper $mapper
     *
     * @return array
     */
    protected function fields($mapper = null)
    {
        $mapper = $mapper ? : $this->mapper;
        $fields = [];
        foreach ($mapper->getFields() as $field) {
            $fields[] = "`{$mapper->getTable()}`.`{$field->getName()}` as `{$this->strategy->getFieldLabel($mapper, $field)}`";
        }

        foreach ($mapper->associations() as $association) {
            $fields = array_merge($fields, $this->fields($association->getMapper()));
        }

        return $fields;
    }

    public function sql()
    {
        return sprintf("select %s from %s %s",
            implode(", ", $this->fields()),
            $this->mapper->getTable() . (implode("", $this->joinClauses())),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : ""
        );
    }
}
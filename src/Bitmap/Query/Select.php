<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Query\Clauses\Join;
use Bitmap\Query\Clauses\Where;
use Bitmap\Query\Context\Context;
use Bitmap\Query\Context\LoadContext;
use Bitmap\Query\Context\QueryContext;
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
    /**
     * @var LoadContext
     */
    protected $context;
    /**
     * @var Where[] $where
     */
    protected $where;
	protected $order;
	protected $limit;
    protected $tables;
    protected $links;
    protected $fields;
    protected $joins;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->where = [];
        $this->order = [];
        $this->with = [];
        $this->tables = [];
        $this->links = [];
        $this->fields = [];
        $this->joins = [];
        $this->strategy = new PrefixStrategy();
        $this->context = new LoadContext($this->mapper);
    }

    /**
     * @param null $connection
     *
     * @return Entity|null
     */
    public function one($connection = null)
    {
        $stmt = $this->execute(Bitmap::current()->connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $this->context);
        return $this->mapper->loadOne($result, $this->context);
    }

    /**
     * @param null $connection
     *
     * @return Entity[]
     */
    public function all($connection = null)
    {
        $stmt = $this->execute(Bitmap::current()->connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $this->context);
        return $this->mapper->loadAll($result, $this->context);
    }

    public function with($context) {
        $this->context->addSubContext($context);

        return $this;
    }

    public function where($field, $operation, $value)
    {
    	if ($this->mapper->hasField($field)) {
		    $this->where[] = (new Where())
                ->setTable($this->mapper->getTable())
                ->setColumn($this->mapper->getField($field)->getColumn())
                ->setOperation($operation)
                ->setValue($value);

		    return $this;
	    } else if ($this->mapper->hasFieldByColumn($field)) {
            $this->where[] = (new Where())
                ->setTable($this->mapper->getTable())
                ->setColumn($this->mapper->getFieldByColumn($field)->getColumn())
                ->setOperation($operation)
                ->setValue($value);

		    return $this;
	    }

	    foreach ($this->mapper->associations() as $association) {
            if ($association->getName() === $field || $association->getColumn() === $field) {
                if ($association->hasLocalValue()) {
                    $this->where[] = (new Where())
                        ->setTable($this->mapper->getTable())
                        ->setColumn($association->getColumn())
                        ->setOperation($operation)
                        ->setValue($value);

                    return $this;
                }

                break;
            }

        }

	    throw new Exception("No field with name '{$field}'");
    }

    public function order($field, $asc = true)
    {
    	$this->order[] = sprintf("`%s` %s", $field, $asc ? 'asc' : 'desc');

    	return $this;
    }

    public function limit($count, $offset = null) {
    	$this->limit = null !== $offset ? [$count, $offset] : [$count];

    	return $this;
    }

	/**
	 * @return string
	 */
	protected function orders()
	{
		return sizeof($this->order) > 0 ? ' order by ' . implode(', ', $this->order) : '';
	}

    public function execute(PDO $connection)
    {
        $columns = [];
        $queue = [$this->context];

        while (!empty($queue)) {
            /** @var LoadContext $context */
            $context = array_shift($queue);
            foreach ($context->getFields() as $name => $field) {
                $columns[] = sprintf('%s.%s as %s',
                    self::escapeName($context->getTableName(), $connection),
                    self::escapeName($field->getColumn(), $connection),
                    self::escapeName("{$context->getTableName()}.{$field->getColumn()}", $connection)
                );
            }

            foreach ($context->getDependencies() as $dependency) {
                $queue[] = $dependency;
            }
        }

        /** @var Join[] $joins */
        $joins = $this->context->getJoins();

        $whereClause = "";
        $params = [];
        if (count($this->where)) {
            $whereClause = " where ";

            foreach ($this->where as /** @var Where */$where) {
                $whereClause .= sprintf(
                    '%s.%s %s %s',
                    self::escapeName($where->getTable(), $connection),
                    self::escapeName($where->getColumn(), $connection),
                    $where->getOperation(),
                    $where->getNumberOfParameters() > 1 ? '(' . implode(',', array_fill(0, $where->getNumberOfParameters(), '?')) . ')' : '?'
                );

                if (is_array($where->getValue())) {
                    foreach ($where->getValue() as $value) {
                        $params[] = $value;
                    }
                } else {
                    $params[] = $where->getValue();
                }

            }
        }

        $joinClause = "";
        foreach ($joins as $join) {
            $joinClause .= sprintf(
                " left join %s %s on %s.%s = %s.%s",
                self::escapeName($join->getToTable(), $connection),
                $join->getToTableAlias(),
                self::escapeName($join->getFromTable(), $connection),
                self::escapeName($join->getFromColumn(), $connection),
                self::escapeName($join->getToTableAlias(), $connection),
                self::escapeName($join->getToColumn(), $connection)
            );
        }

        $sql = sprintf('select %s from %s%s%s%s%s',
            implode(", ", array_values($columns)),
            self::escapeName($this->context->getTableName(), $connection),
            $joinClause,
            $whereClause,
            $this->orders(),
            null !== $this->limit ? " limit " .(sizeof($this->limit) === 2 ? "{$this->limit[1]}, " : ''). "{$this->limit[0]}"  : ''
        );

        Bitmap::current()->getLogger()->info("Running query",
            [
                'mapper' => $this->mapper->getClass(),
                'sql'    => $sql
            ]
        );

        $statement = $connection->prepare($sql);

        if (!$statement->execute($params)) {
            throw new Exception(sprintf("[%s]", implode(", ", array_values($statement->errorInfo())),  $statement->errorCode()));
        }

        return $statement;
    }
}
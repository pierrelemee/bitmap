<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Query\Context\Context;
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
	protected $order;
	protected $limit;
    /**
     * @var Context
     */
    protected $context;
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
    }

    public function execute(PDO $connection)
    {
        $sql = $this->sql();
        Bitmap::current()->getLogger()->info("Running query",
            [
                'mapper' => $this->mapper->getClass(),
                'sql'    => $sql
            ]
        );
        return $connection->query($sql, $this->strategy->getPdoFetchingType());
    }

    /**
     * @param array|null $with
     * @param null $connection
     *
     * @return Entity|null
     */
    public function one($with = null, $connection = null)
    {
        $this->context = new Context($this->mapper, $with);
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $this->context);

        return $this->mapper->loadOne($result, $this->context);
    }

    /**
     * @param array|null $with
     * @param null $connection
     *
     * @return Entity[]
     */
    public function all($with = null, $connection = null)
    {
        $this->context = new Context($this->mapper, $with);
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $this->context);

        return $this->mapper->loadAll($result, $this->context);
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

	    foreach ($this->mapper->associations() as $association) {
            if ($association->getName() === $field || $association->getColumn() === $field) {
                if ($association->hasLocalValue()) {
                    $this->where[] = sprintf(
                        "`%s`.`%s` %s %s",
                        $this->mapper->getTable(),
                        $association->getColumn(),
                        $operation,
                        $value
                    );

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

    protected function mapperDepth(Mapper $mapper)
    {
        if (!isset($this->tables[$mapper->getTable()])) {
            $this->tables[$mapper->getTable()] = 0;
        }

        return $this->tables[$mapper->getTable()];
    }

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

        return $fields;
    }

	/**
	 * @return string
	 */
	protected function orders()
	{
		return sizeof($this->order) > 0 ? ' order by ' . implode(', ', $this->order) : '';
	}

    protected function tables(Mapper $mapper, Context $context)
    {
        if (!isset($this->tables[$context->getMapper()->getClass()][$context->getDepth()])) {
            if (!isset($this->tables[$context->getMapper()->getClass()])) {
                $this->tables[$context->getMapper()->getClass()] = [];
            }

            $this->tables[$context->getMapper()->getClass()][$context->getDepth()] = true;
        }

        foreach ($mapper->getFields() as $field) {
            $this->fields[] = sprintf(
                "`%s`.`%s` as `%s`",
                $mapper->getTable() . ($context->getDepth() > 0 ? $context->getDepth() : ''),
                $field->getName(),
                $this->strategy->getFieldLabel($mapper, $field, $context->getDepth())
            );
        }

        foreach ($context->getDependencies() as $name => $subcontext) {
            if (!isset($this->tables[$subcontext->getMapper()->getClass()][$subcontext->getDepth()])) {
                $this->joins = array_merge(
                    $this->joins,
                    $mapper->getAssociation($name)->joinClauses(
                        $mapper->getTable(),
                        $subcontext->getMapperDepth()
                    )
                );

                $this->tables($mapper->getAssociation($name)->getMapper(), $subcontext);
            }
        }
    }

    public function sql()
    {
        $this->tables($this->mapper, $this->context);

        return sprintf("select %s from %s %s%s%s",
            implode(", ", $this->fields),
            $this->mapper->getTable() . (implode("", $this->joins)),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : "",
            $this->orders(),
            null !== $this->limit ? " limit " .(sizeof($this->limit) === 2 ? "{$this->limit[1]}, " : ''). "{$this->limit[0]}"  : ''
        );
    }
}
<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Query\Context\Context;
use Bitmap\Query\Context\LoadContext;
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
        $this->context = new LoadContext($this->mapper, $with);
        $stmt = $this->execute(Bitmap::current()->connection($connection));
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
        $this->context = new LoadContext($this->mapper, $with);
        $stmt = $this->execute(Bitmap::current()->connection($connection));
        $result = new ResultSet($stmt, $this->mapper, $this->strategy, $this->context);

        return $this->mapper->loadAll($result, $this->context);
    }

    public function where($field, $operation, $value)
    {
    	if ($this->mapper->hasField($field)) {
		    $this->where[] = sprintf(
			    "`%s`.`%s` %s %s",
			    $this->mapper->getTable(),
			    $this->mapper->getField($field)->getColumn(),
			    $operation,
			    $this->mapper->getField($field)->getTransformer()->fromObject($value)
		    );

		    return $this;
	    } else if ($this->mapper->hasFieldByColumn($field)) {
		    $this->where[] = sprintf(
			    "`%s`.`%s` %s %s",
			    $this->mapper->getTable(),
			    $this->mapper->getFieldByColumn($field)->getColumn(),
			    $operation,
			    $this->mapper->getFieldByColumn($field)->getTransformer()->fromObject($value)
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

	/**
	 * @return string
	 */
	protected function orders()
	{
		return sizeof($this->order) > 0 ? ' order by ' . implode(', ', $this->order) : '';
	}

    public function sql()
    {
        return sprintf("select %s from %s %s%s%s",
            implode(", ", $this->context->getFields($this->strategy)),
            $this->context->getTableName() . (implode("", $this->context->getJoins())),
            sizeof($this->where) > 0 ? " where " . implode(" and ", $this->where) : "",
            $this->orders(),
            null !== $this->limit ? " limit " .(sizeof($this->limit) === 2 ? "{$this->limit[1]}, " : ''). "{$this->limit[0]}"  : ''
        );
    }
}
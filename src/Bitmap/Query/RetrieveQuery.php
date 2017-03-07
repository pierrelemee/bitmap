<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use Bitmap\ResultSet;
use PDO;

abstract class RetrieveQuery extends Query
{
    /**
     * @var
     */
    protected $strategy;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
        $this->strategy = $this->fieldMappingStrategy();
    }

    public function execute(PDO $connection)
    {
        return $connection->query($this->sql(), $this->strategy->getPdoFetchingType());
    }

    /**
     * @return FieldMappingStrategy
     */
    protected abstract function fieldMappingStrategy();

    /**
     * @param null $connection
     *
     * @return Entity|null
     */
    public function one($connection = null)
    {
        //$strategy = $strategy ? : $this->fieldMappingStrategy();
        $stmt = $this->execute(Bitmap::connection($connection));
        $result = new ResultSet($stmt, $this->mapper);

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
        $result = new ResultSet($stmt, $this->mapper);

        return $this->mapper->loadAll($result);
    }
}
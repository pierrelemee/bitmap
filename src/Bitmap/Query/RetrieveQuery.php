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
     * @var FieldMappingStrategy
     */
    protected $strategy;

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
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
}
<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
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
     * @param array $with
     *
     * @return Entity|null
     */
    public function one($connection = null, $with = [])
    {
        $stmt = $this->execute(Bitmap::connection($connection));

        if (false !== $stmt) {
            if (false !== ($data = $stmt->fetch())) {
                return $this->mapper->load($data, $this->strategy);
            }
        }

        return null;
    }

    /**
     * @param null $connection
     * @param array $with
     *
     * @return Entity[]
     */
    public function all($connection = null, $with = [])
    {
        $stmt = $this->execute(Bitmap::connection($connection));
        $entities = [];

        if (false !== $stmt) {
            while (false !== ($data = $stmt->fetch())) {
                $entities[] = $this->mapper->load($data, $with);
            }
        }

        return $entities;
    }
}
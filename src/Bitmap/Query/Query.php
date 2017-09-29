<?php

namespace Bitmap\Query;

use Bitmap\Mapper;
use PDO;

abstract class Query
{
    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public abstract function execute(PDO $connection);

    /**
     * @param PDO $connection
     *
     * @return string
     */
    public abstract function sql(PDO $connection);

    /**
     * @param $name
     * @param PDO $connection
     *
     * @return string
     */
    protected function escapeName($name, PDO $connection)
    {
        return "{$this->getEscapeCharacter($connection)}$name{$this->getEscapeCharacter($connection)}";
    }

    private function getEscapeCharacter(PDO $connection)
    {
        var_dump($connection->getAttribute(PDO::ATTR_DRIVER_NAME));
        return $connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql' ? '"' : '`';
    }
}
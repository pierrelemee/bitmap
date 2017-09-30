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
    public static function escapeName($name, PDO $connection)
    {
        return self::getEscapeCharacter($connection) . $name . self::getEscapeCharacter($connection);
    }

    private static function getEscapeCharacter(PDO $connection)
    {
        return $connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql' ? '"' : '`';
    }
}
<?php

namespace Bitmap\Query;


use Bitmap\Exceptions\QueryException;
use PDO;

abstract class ModifyQuery extends Query
{
    /**
     * @param PDO $connection
     *
     * @return int
     *
     * @throws QueryException
     */
    public function execute(PDO $connection)
    {
        $sql = $this->sql();
        $count = $connection->exec($sql);

        if (false === $count) {
            throw new QueryException($sql, $connection);
        }

        return $count;
    }
}